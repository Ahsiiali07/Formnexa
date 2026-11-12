<?php

namespace App\Http\Controllers;

use App\Services\ConverterDispatcher;
use App\Support\ConverterCatalog;
use App\Support\ToolSeoCatalog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class ConverterController extends Controller
{
    /**
     * All PDF Conversions Directory Hub (/convert)
     */
    public function hub(): View
    {
        return view('convert.hub', [
            'groups' => ConverterCatalog::grouped(),
            'tools' => ConverterCatalog::all(),
        ]);
    }

    /**
     * Individual Converter Tool Workspace (/convert/{tool})
     */
    public function tool(string $tool): View
    {
        $meta = ConverterCatalog::find($tool);
        abort_if($meta === null, 404);

        return view('convert.tool', [
            'meta' => $meta,
            'groups' => ConverterCatalog::grouped(),
            'convSeo' => ToolSeoCatalog::converter($tool),
        ]);
    }

    /**
     * Execute server-side file conversion & return binary attachment stream (/api/convert/{tool})
     */
    public function process(Request $request, string $tool, ConverterDispatcher $dispatcher): Response|JsonResponse
    {
        $meta = ConverterCatalog::find($tool);
        abort_if($meta === null, 404);

        try {
            $result = $dispatcher->dispatch($request, $tool);

            if (($result['mode'] ?? null) === 'json') {
                return response()->json([
                    'success' => true,
                    'num_pages' => $result['num_pages'],
                    'rendered_count' => $result['rendered_count'],
                    'pages' => $result['pages'],
                ]);
            }

            return response($result['bytes'], 200, [
                'Content-Type' => $result['mime'],
                'Content-Disposition' => 'attachment; filename="' . $result['filename'] . '"',
                'X-Converted-Filename' => $result['filename'],
                'Cache-Control' => 'no-store, no-cache',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => collect($e->errors())->flatten()->first() ?: 'Invalid file or options.',
            ], 422);
        } catch (\RuntimeException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        } catch (\Throwable $e) {
            report($e);
            return response()->json([
                'success' => false,
                'message' => 'Conversion failed: ' . $e->getMessage(),
            ], 422);
        }
    }
}
