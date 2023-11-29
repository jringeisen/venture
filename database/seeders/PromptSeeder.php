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
                'prompt' => <<<EOT
                Analyze the question, and only choose from the Subject Category List below. Create a json response with a subject and sub_category.

                ONLY choose from the options below, do not create new categories.

                SUBJECT CATEGORY LIST CHOICE:

                Subject Category | English
                English Subcategory | Grammar and Syntax
                English Subcategory | Vocabulary
                English Subcategory | Reading Comprehension
                English Subcategory | Writing
                English Subcategory | Literature
                English Subcategory | Communication
                English Subcategory | Research Skills
                English Subcategory | Language and Culture
                Subject Category | Mathematics
                Mathematics Subcategory | Arithmetic
                Mathematics Subcategory | Algebra
                Mathematics Subcategory | Geometry
                Mathematics Subcategory | Trigonometry
                Mathematics Subcategory | Calculus
                Mathematics Subcategory | Statistics
                Mathematics Subcategory | Probability
                Mathematics Subcategory | Discrete Mathematics
                Mathematics Subcategory | Applied Mathematics
                Subject Category | Science
                Science Subcategory | Physics
                Science Subcategory | Chemistry
                Science Subcategory | Biology
                Science Subcategory | Earth Science
                Science Subcategory | Environmental Science
                Science Subcategory | Anatomy and Physiology
                Science Subcategory | Geographical Sciences
                Science Subcategory | Paleontology
                Science Subcategory | Scientific Inquiry and Methodology
                Subject Category | Social Studies
                Social Studies Subcategory | History
                Social Studies Subcategory | Geography
                Social Studies Subcategory | Civics and Government
                Social Studies Subcategory | Economics
                Social Studies Subcategory | Sociology
                Social Studies Subcategory | Anthropology
                Social Studies Subcategory | Archaeology
                Social Studies Subcategory | Cultural Studies
                Social Studies Subcategory | Psychology
                Social Studies Subcategory | Religion and Belief Systems
                Subject Category  | Physical Education
                Physical Education Subcategory | Fitness and Exercise
                Physical Education Subcategory | Sports and Games
                Physical Education Subcategory | Health and Wellness
                Physical Education Subcategory | Outdoor and Adventure Activities
                Physical Education Subcategory | Fitness Assessments and Measurements
                Physical Education Subcategory | Rules and Strategies
                Subject Category | Arts
                Arts Subcategory | Visual Arts
                Arts Subcategory | Performing Arts
                Arts Subcategory | Literary Arts
                Arts Subcategory | Design
                Arts Subcategory | Craft and Artisanal Skills
                Arts Subcategory | Art History and Criticism
                Arts Subcategory | Media Arts
                Subject Category | Technology
                Technology Subcategory | Programming and Software Development
                Technology Subcategory | Computer Systems and Networks
                Technology Subcategory | Cybersecurity
                Technology Subcategory | Data Management and Analytics
                Technology Subcategory | Information Systems
                Technology Subcategory | Web Design and Development
                Technology Subcategory | Robotics and Automation
                Technology Subcategory | Multimedia and Graphics
                Subject Category | Health Education
                Health Education Subcategory | Physical Health
                Health Education Subcategory | Mental and Emotional Health
                Health Education Subcategory | Sexual and Reproductive Health
                Health Education Subcategory | Disease Prevention and Control
                Health Education Subcategory | Substance Abuse Education
                Health Education Subcategory | First Aid and Emergency Preparedness
                Health Education Subcategory | Environmental Health
                Health Education Subcategory | Health Advocacy and Decision Making
                Subject Category | Career and Technical Education
                Career and Technical Education Subcategory | Business Management and Administration
                Career and Technical Education Subcategory | Agriculture, Food, and Natural Resources
                Career and Technical Education Subcategory | Health Science
                Career and Technical Education Subcategory | Information Technology
                Career and Technical Education Subcategory | Architecture and Construction
                Career and Technical Education Subcategory | Manufacturing and Engineering
                Career and Technical Education Subcategory | Arts, Audio/Video Technology, and Communication
                Career and Technical Education Subcategory | Transportation, Distribution, and Logistics
                Career and Technical Education Subcategory | Education and Training
                Career and Technical Education Subcategory | Hospitality and Tourism
                Career and Technical Education Subcategory | Human Services
                Career and Technical Education Subcategory | Law, Public Safety, Corrections, and Security
                Subject Category | Life Skills
                Life Skills Subcategory | Personal Development
                Life Skills Subcategory | Health and Well-being
                Life Skills Subcategory | Financial
                Life Skills Subcategory | Career Development
                Life Skills Subcategory | Interpersonal Skills
                Life Skills Subcategory | Critical Thinking and Problem-Solving
                Life Skills Subcategory | Home Management
                Life Skills Subcategory | Digital Literacyd
                EOT
            ],
            [
                'category' => 'tone',
                'prompt' => <<<EOT
                Answer this question in an informative and engaging way,
                presenting information about the topic to someone at an age level of [AGE]
                and a grade level of [GRADE]. The tone of the text is enthusiastic, friendly,
                and playful. It conveys excitement and wonder about the subject matter, aiming
                to engage and entertain the reader. The voice is conversational, speaking directly
                to the reader in a friendly and approachable manner. It adopts the tone of an
                enthusiastic and knowledgeable friend, eager to share fascinating information.
                The response should be json with a key of content and the content should
                not include any emojis.
                EOT
            ],
            [
                'category' => 'questions',
                'prompt' => <<<EOT
                Based on this question, create an json response with a list of 5 to 10 additional questions
                with the key being "questions".
                EOT
            ],
            [
                'category' => 'moderation',
                'prompt' => <<<EOT
                Analyze the question and decide if it is an appropriate question for a 8-13 year old. If it's not, reply with the correct response below.
                Return json with a key of flagged = True and a key of Message = "I understand you have a question, but the topic you're asking about is complex and requires guidance from a responsible adult. It's important to have these discussions in a safe and appropriate environment. I encourage you to talk to a trusted adult, such as a parent or guardian, who can guide you through a thoughtful and meaningful conversation. They can provide you with the guidance to address this question appropriately."
                Flag the following content as inappropriate:
                1. Hate: Content that expresses, incites, or promotes hate based on race, gender, ethnicity, religion, nationality, sexual orientation, disability status, or caste.
                2. Hate/threatening: Hateful content that also includes violence or serious harm towards the targeted group.
                3. Self-harm: Content that promotes, encourages, or depicts acts of self-harm, such as suicide, cutting, and eating disorders.
                4. Sexual: Content meant to arouse sexual excitement, such as the description of sexual activity, or that promotes sexual services (excluding sex education and wellness).
                5. Sexual/minors: Sexual content that includes an individual who is under 18 years old.
                6. Violence: Content that promotes or glorifies violence or celebrates the suffering or humiliation of others.
                7. Violence/graphic: Violent content that depicts death, violence, or serious physical injury in extreme graphic detail.
                EOT
            ]
        ])->each(function ($prompt) {
            Prompt::create($prompt);
        });
    }
}
