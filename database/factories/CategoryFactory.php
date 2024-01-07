<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $categories = [
            "Lifestyle",
            "Technology",
            "Travel",
            "Food & Cooking",
            "Health & Fitness",
            "Fashion & Beauty",
            "Entertainment",
            "Business & Finance",
            "Education & Careers",
            "Art & Culture",
            "Science & Environment",
            "Personal Stories & Opinions",
            "Parenting & Family",
            "DIY & Crafts",
            "News & Politics",
            "Sports & Recreation",
            "Photography & Videography",
            "Gaming & Esports",
            "Pets & Animals",
            "Automotive & Transport",
            "Gardening & Horticulture",
            "Music & Instruments",
            "Film & Theatre",
            "Book Reviews & Literature",
            "Comedy & Humor",
            "History & Archaeology",
            "Psychology & Sociology",
            "Home Improvement",
            "Investing & Trading",
            "Legal & Law",
            "Real Estate",
            "Graphic Design & Digital Art",
            "Philosophy & Ethics",
            "Space & Astronomy",
            "Language Learning & Linguistics",
            "Podcasting & Broadcasting",
            "Craft Beer & Brewing",
            "Yoga & Mindfulness",
            "Outdoor Adventures & Survival",
            "Volunteering & Social Work",
            "Sustainable Living & Eco-Friendly Practices",
            "Virtual Reality & Augmented Reality",
            "Blockchain & Cryptocurrency",
            "Robotics & Automation",
            "Cybersecurity & Data Privacy",
            "Biohacking & Transhumanism",
            "Event Planning & Management",
            "Handicrafts & Traditional Arts",
            "Animation & Motion Graphics",
            "Urban Exploration & City Life"
        ];

        // Select a random user
        $user = User::inRandomOrder()->first();

        // Get category names already assigned to the user
        $assignedCategories = $user->categories->pluck('name')->toArray();

        // Filter out the assigned categories from the original list
        $availableCategories = array_diff($categories, $assignedCategories);

        // Select a random category from the available ones
        $categoryName = Arr::random($availableCategories);

        return [
            'user_id' => User::inRandomOrder()->first()->id,
            'name' => $categoryName,
            'slug' => Str::slug($categoryName),
        ];
    }
}
