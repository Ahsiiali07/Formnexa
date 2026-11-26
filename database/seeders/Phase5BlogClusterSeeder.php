<?php

namespace Database\Seeders;

use App\Models\BlogPost;
use Illuminate\Database\Seeder;

class Phase5BlogClusterSeeder extends Seeder
{
    /**
     * Seed the 12 Phase 5 cluster posts across ATS, Resignation, and Paystub/Salary Slip topics.
     * Created as unpublished drafts by default (is_published = false) for review prior to publishing.
     */
    public function run(): void
    {
        $posts = array_merge(
            require __DIR__ . '/data/ats_posts.php',
            require __DIR__ . '/data/resignation_posts.php',
            require __DIR__ . '/data/paystub_posts.php'
        );

        foreach ($posts as $post) {
            BlogPost::updateOrCreate(
                ['slug' => $post['slug']],
                $post
            );
        }
    }
}
