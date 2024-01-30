<?php

namespace Database\Seeders;

use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\User;
use Illuminate\Database\Seeder;

class BlogPostSeeder extends Seeder
{
    public function run(): void
    {
        $userId = User::first()->id;

        $blogPosts = [
            [
                'user_id' => $userId,
                'blog_category_id' => BlogCategory::where('slug', 'marine-biology')->first()->id,
                'title' => 'Exploring the Depths: A Journey into Marine Biology',
                'slug' => 'exploring-marine-biology',
                'content' => "<h2>Introduction to Marine Biology</h2>
                <p>
                    Marine biology is a fascinating and diverse field, encompassing the study of life in the oceans and other saltwater environments like estuaries and wetlands. This blog post aims to take you on a journey through the wonders of marine biology, exploring the mysterious depths of our oceans and the incredible life forms that inhabit them.
                </p>

                <h2>The Mysteries of the Deep Sea</h2>
                <p>
                    The deep sea is one of the least explored and understood habitats on Earth. It's home to some of the most unusual and enigmatic creatures, such as the bioluminescent jellyfish, the elusive giant squid, and the bizarre-looking anglerfish. These creatures have adapted in remarkable ways to survive in the extreme conditions of the deep ocean, including high pressure, low temperatures, and complete darkness.
                </p>

                <h2>Marine Ecosystems and Their Importance</h2>
                <p>
                    Our oceans are home to a diverse range of ecosystems, from coral reefs and kelp forests to the vast open ocean and deep sea vents. These ecosystems are vital for the health of our planet, providing essential services such as oxygen production, carbon sequestration, and climate regulation. They are also a source of food, medicine, and recreation for people around the world.
                </p>

                <h2>Human Impact on Marine Life</h2>
                <p>
                    Human activities have a significant impact on marine ecosystems. Pollution, overfishing, climate change, and habitat destruction are just a few of the ways we are affecting ocean life. Understanding these impacts is crucial for developing strategies to mitigate them and protect our marine environments.
                </p>

                <h2>The Future of Marine Biology</h2>
                <p>
                    As we continue to explore and understand the depths of our oceans, marine biology is poised to make even more exciting discoveries in the future. Advances in technology, such as deep-sea submersibles and satellite tracking, are opening up new opportunities for research and conservation. The future of marine biology offers a hopeful promise for the preservation and deeper understanding of our ocean's mysteries.
                </p>

                <h2>Conclusion</h2>
                <p>
                    Marine biology is not just about exploring the unknown and discovering new species; it's also about understanding our impact on the ocean and finding ways to coexist sustainably with its inhabitants. As we delve deeper into marine biology, we unlock the secrets of the ocean and learn more about the vital role it plays in our world.
                </p>",
                'excerpt' => 'Dive into the fascinating world of marine biology and discover the wonders of ocean life.',
                'featured_image' => 'https://images.pexels.com/photos/5967799/pexels-photo-5967799.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                'is_published' => true,
                'meta_title' => 'Marine Biology Exploration',
                'meta_description' => 'Join us in exploring the depths of the ocean to uncover the secrets of marine biology.',
            ],
            [
                'user_id' => $userId,
                'blog_category_id' => BlogCategory::where('slug', 'creative-writing')->first()->id,
                'title' => 'The Art of Storytelling: Tips from Creative Writing',
                'slug' => 'art-of-storytelling',
                'content' => 'Content covering creative writing techniques, narrative development, and character creation.',
                'excerpt' => 'Unlock the secrets of creative writing and learn the art of crafting compelling stories.',
                'featured_image' => 'https://images.pexels.com/photos/68562/pexels-photo-68562.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                'is_published' => true,
                'meta_title' => 'Creative Writing Mastery',
                'meta_description' => 'Explore the intricacies of creative writing and become a master storyteller.',
            ],
            [
                'user_id' => $userId,
                'blog_category_id' => BlogCategory::where('slug', 'science')->first()->id,
                'title' => 'The Wonders of Science: Exploring Our World',
                'slug' => 'wonders-of-science',
                'content' => 'Content discussing various scientific fields, discoveries, and their impacts on our daily lives.',
                'excerpt' => 'Discover the many wonders of science and how they shape our understanding of the world.',
                'featured_image' => 'https://images.pexels.com/photos/414860/pexels-photo-414860.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                'is_published' => true,
                'meta_title' => 'Discovering Science',
                'meta_description' => 'Embark on a journey of scientific discovery and explore the marvels of our universe.',
            ],
            [
                'user_id' => $userId,
                'blog_category_id' => BlogCategory::where('slug', 'mathematics')->first()->id,
                'title' => 'Mathematics: The Language of the Universe',
                'slug' => 'mathematics-universe-language',
                'content' => 'Content focusing on the beauty of mathematics, its principles, and real-world applications.',
                'excerpt' => 'Explore how mathematics helps us decode the universe and solve complex problems.',
                'featured_image' => 'https://images.pexels.com/photos/714699/pexels-photo-714699.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                'is_published' => true,
                'meta_title' => 'Mathematics in Everyday Life',
                'meta_description' => 'Delve into the world of mathematics and its fascinating role in understanding the universe.',
            ],
            [
                'user_id' => $userId,
                'blog_category_id' => BlogCategory::where('slug', 'physics')->first()->id,
                'title' => 'Unveiling the Mysteries of Physics',
                'slug' => 'mysteries-of-physics',
                'content' => 'Content exploring fundamental physics concepts and their implications in our universe.',
                'excerpt' => 'Join us as we unravel the mysteries of physics and its profound impact on our understanding of reality.',
                'featured_image' => 'https://images.pexels.com/photos/2366581/pexels-photo-2366581.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                'is_published' => true,
                'meta_title' => 'Exploring Physics',
                'meta_description' => 'Dive deep into the world of physics and discover the forces that govern our universe.',
            ],
            [
                'user_id' => $userId,
                'blog_category_id' => BlogCategory::where('slug', 'chemistry')->first()->id,
                'title' => 'Chemistry: The Elemental Building Blocks',
                'slug' => 'chemistry-elemental-building-blocks',
                'content' => 'Content highlighting the significance of chemistry in understanding matter and its transformations.',
                'excerpt' => 'Discover the elemental world of chemistry and its role in shaping everything around us.',
                'featured_image' => 'https://images.pexels.com/photos/3735757/pexels-photo-3735757.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                'is_published' => true,
                'meta_title' => 'The World of Chemistry',
                'meta_description' => 'Explore the fascinating field of chemistry and learn about the building blocks of life.',
            ],
            [
                'user_id' => $userId,
                'blog_category_id' => BlogCategory::where('slug', 'biology')->first()->id,
                'title' => 'Biology: Understanding Life and Living Organisms',
                'slug' => 'understanding-biology',
                'content' => 'Content covering diverse aspects of biology, from cellular processes to ecosystem dynamics.',
                'excerpt' => 'Embark on a journey through the world of biology to understand the intricacies of life and living organisms.',
                'featured_image' => 'https://images.pexels.com/photos/842401/pexels-photo-842401.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                'is_published' => true,
                'meta_title' => 'The Science of Biology',
                'meta_description' => 'Dive into biology to explore the complex interactions and phenomena of living organisms.',
            ],
        ];

        foreach ($blogPosts as $blogPost) {
            BlogPost::updateOrCreate(['slug' => $blogPost['slug']], $blogPost);
        }
    }
}
