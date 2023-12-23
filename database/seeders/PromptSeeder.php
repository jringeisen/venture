<?php

namespace Database\Seeders;

use App\Models\Prompt;
use Illuminate\Database\Seeder;

class PromptSeeder extends Seeder
{
    public function run(): void
    {
        collect([
            [
                'category' => 'categorize',
                'prompt' => <<<'EOT'
                Analyze the question and categorize it using the provided Subject Category List. Respond in JSON format with 'subject' and 'sub_category' fields. Choose only from the listed categories:
                - English: [Grammar and Syntax, Vocabulary, Reading Comprehension, Writing, Literature, Communication, Research Skills, Language and Culture]
                - Mathematics: [Arithmetic, Algebra, Geometry, Trigonometry, Calculus, Statistics, Probability, Discrete Mathematics, Applied Mathematics]
                - Science: [Physics, Chemistry, Biology, Earth Science, Environmental Science, Anatomy and Physiology, Geographical Sciences, Paleontology, Scientific Inquiry and Methodology]
                - [Social Studies, Physical Education, Arts, Technology, Health Education, Career and Technical Education, Life Skills]
                Do not create new categories.
                EOT
            ],
            [
                'category' => 'tone',
                'prompt' => <<<'EOT'
                Please provide an informative and engaging response. This explanation is for a student who is [AGE]
                years old and in [GRADE] grade. The response should be tailored to this age and grade level, using simple
                language and avoiding technical jargon. Aim for a tone that is enthusiastic, friendly, and playful, as if
                you're an excited and knowledgeable friend sharing fascinating facts. The content should be engaging,
                possibly including a question, fun fact, or analogy that is relatable to a [AGE]-year-old. The response
                should be concise, ideally no more than 250 words, to keep it attention-grabbing. End with a strong
                conclusion that summarizes the key points in a memorable and entertaining way.
                EOT
            ],
            [
                'category' => 'questions',
                'prompt' => <<<'EOT'
                Create an curriculum outline for the subject for the kids to learn more about the topic below.
                Don't number anything and simply list out the topics to the question. Each of the topics needs
                to be able to be on it's own. (exclude things like "conclusion" "Wrapping up"). Give me the results
                in a list and in json format with a key of questions. Include at least 10 items in the list.
                EOT
            ],
            [
                'category' => 'moderation',
                'prompt' => <<<'EOT'
                Review the question for suitability for ages 8-13. If inappropriate, flag it. Return JSON with 'flagged' = True and a 'message' explaining why it's not suitable, advising to discuss with a trusted adult. Flag content involving:
                1. Hate: Prejudice based on race, gender, ethnicity, religion, etc.
                2. Hate/threatening: Hateful content with violence or harm.
                3. Self-harm: Promotion or depiction of self-harm (e.g., suicide, eating disorders).
                4. Sexual: Arousing or explicit sexual content, excluding educational material.
                5. Sexual/minors: Sexual content involving under-18 individuals.
                6. Violence: Promotion or glorification of violence.
                7. Violence/graphic: Extremely graphic violent content.
                8. Controversial topics such as gender identity and identity politics.
                Use this guidance strictly.
                EOT
            ],
        ])->each(function ($prompt) {
            Prompt::updateOrCreate(
                ['category' => $prompt['category']],
                ['prompt' => $prompt['prompt']]
            );
        });
    }
}
