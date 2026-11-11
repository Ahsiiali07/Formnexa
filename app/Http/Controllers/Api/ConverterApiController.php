<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ConverterDispatcher;
use App\Support\ConverterCatalog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class ConverterApiController extends Controller
{
    /**
     * Execute any of the 10 PDF conversion tools for mobile API clients.
     */
    public function process(Request $request, string $tool, ConverterDispatcher $dispatcher): Response|JsonResponse
    {
        $meta = ConverterCatalog::find($tool);
        if ($meta === null) {
            return response()->json([
                'success' => false,
                'message' => 'Converter tool not found.',
            ], 404);
        }

        try {
            $result = $dispatcher->dispatch($request, $tool);

            if (($result['mode'] ?? null) === 'json') {
                return response()->json([
                    'success' => true,
                    'tool' => $tool,
                    'num_pages' => $result['num_pages'],
                    'rendered_count' => $result['rendered_count'],
                    'pages' => $result['pages'],
                ]);
            }

            return response($result['bytes'], 200, [
                'Content-Type' => $result['mime'],
                'Content-Disposition' => 'attachment; filename="' . $result['filename'] . '"',
                'X-Converted-Filename' => $result['filename'],
                'Content-Length' => (string) strlen($result['bytes']),
                'Cache-Control' => 'no-store, no-cache',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => collect($e->errors())->flatten()->first() ?: 'Invalid file or options.',
                'errors' => $e->errors(),
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
