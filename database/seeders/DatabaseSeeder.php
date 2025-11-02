<?php

namespace Database\Seeders;

use App\Models\Brief;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('secret'),
        ]);

        $briefs = [
            [
                'title' => 'Gym Brand Refresh',
                'raw_input' => 'Hey so we’re looking to maybe redo our brand? Not a full rebrand, just like, you know, freshen it up a bit. 
                    The logo feels kind of dated and the colors don’t pop on Instagram. 
                    We want something clean but still fun — maybe like Airbnb but not exactly that. 
                    We’ll need new icons for the app too — maybe vector style? Timeline-wise we’re trying to push something out by next month 
                    because we’ve got a fitness expo coming up in LA. Budget isn’t huge but we can stretch if it’s worth it.',
                'type' => 'Branding',
                'structured_output' => null,
                'user_id' => 1,
                'status' => 'draft',
            ],
            [
                'title' => 'Summer Ads Campaign',
                'raw_input' => 'We need something to push summer sales for our eco bottles. Maybe video ads? 
                    Instagram and TikTok are our main focus. Need short, punchy copy. 
                    The campaign should look playful but keep sustainability at the core.',
                'type' => 'Marketing',
                'structured_output' => null,
                'user_id' => 1,
                'status' => 'draft',
            ],
            [
                'title' => 'New SaaS Dashboard UI',
                'raw_input' => 'Our startup’s admin dashboard looks outdated and clunky. 
                    We want a cleaner UI, modern color palette, and improved typography. 
                    Needs to work well on tablets too. 
                    Timeline is about six weeks. 
                    Not a full rebrand, just visual refresh and UX polish.',
                'type' => 'Web App',
                'structured_output' => null,
                'user_id' => 1,
                'status' => 'draft',
            ],
            [
                'title' => 'Podcast Brand Identity',
                'raw_input' => 'Launching a podcast about remote work culture. 
                    We need a visual identity — cover art, social post template, and typography. 
                    Tone should feel calm, intelligent, but approachable. 
                    Think Notion meets TED. Hoping to launch next month.',
                'type' => 'Branding',
                'structured_output' => null,
                'user_id' => 1,
                'status' => 'draft',
            ],
            [
                'title' => 'Bakery Website Revamp',
                'raw_input' => 'We run a local bakery and our website feels really old. 
                    Need something more visual with better photos and easy online ordering. 
                    Customers mostly use phones to browse. 
                    We’d like to add an option for pre-ordering cakes too.',
                'type' => 'Web App',
                'structured_output' => null,
                'user_id' => 1,
                'status' => 'draft',
            ],
        ];

        Brief::insert($briefs);
    }
}
