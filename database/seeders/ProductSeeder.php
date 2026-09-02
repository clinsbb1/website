<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Migrates the products previously hardcoded in the static homepage.
     * No metrics are set — none were verified, and the brief is explicit
     * about not inventing them. Workflix / AutobillsPro have no live URL
     * yet, matching the old site's "URL coming soon" state.
     */
    public function run(): void
    {
        $products = [
            [
                'name' => 'InVyt Access',
                'slug' => 'invyt-access',
                'description' => 'Guest management and event-access platform for invitations, RSVP management, QR check-in and event communications.',
                'status' => Product::STATUS_LIVE,
                'website_url' => 'https://invyt.ng',
                'featured' => true,
                'published' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Everything Tax',
                'slug' => 'everything-tax',
                'description' => 'Tax education and advisory platform helping individuals, businesses and professionals understand and navigate Nigerian taxes.',
                'status' => Product::STATUS_LIVE,
                'website_url' => 'https://everythingtax.ng',
                'featured' => true,
                'published' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Quickplanners',
                'slug' => 'quickplanners',
                'description' => 'Event planning platform that secures venues, connects users with verified vendors, and handles the full planning process when needed.',
                'status' => Product::STATUS_LIVE,
                'website_url' => 'https://quickplanners.com',
                'featured' => true,
                'published' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'FeedLog',
                'slug' => 'feedlog',
                'description' => 'Turns scattered feedback — text, video or voice — into AI-tracked tasks, keeping everyone involved informed until they\'re resolved.',
                'status' => Product::STATUS_LIVE,
                'website_url' => 'https://feedlog.io',
                'featured' => true,
                'published' => true,
                'sort_order' => 4,
            ],
            [
                'name' => 'Workflix',
                'slug' => 'workflix',
                'description' => 'A streamlined platform helping freelancers and small software agencies organise projects, manage clients, and handle essential business operations in one place.',
                'status' => Product::STATUS_IN_DEVELOPMENT,
                'website_url' => null,
                'featured' => false,
                'published' => true,
                'sort_order' => 5,
            ],
            [
                'name' => 'AutobillsPro',
                'slug' => 'autobillspro',
                'description' => 'Automates bill payments so users never miss due dates, avoid late fees, and manage personal and family bills from one platform.',
                'status' => Product::STATUS_IN_PROGRESS,
                'website_url' => null,
                'featured' => false,
                'published' => true,
                'sort_order' => 6,
            ],
        ];

        foreach ($products as $product) {
            Product::updateOrCreate(['slug' => $product['slug']], $product);
        }
    }
}
