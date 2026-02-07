<?php

namespace App\Enums;

enum AgeGroup: string
{
    case Elementary = 'elementary';
    case Middle = 'middle';
    case High = 'high';

    public function minAge(): int
    {
        return match ($this) {
            self::Elementary => 5,
            self::Middle => 11,
            self::High => 14,
        };
    }

    public function maxAge(): int
    {
        return match ($this) {
            self::Elementary => 10,
            self::Middle => 13,
            self::High => 18,
        };
    }

    public function label(): string
    {
        return match ($this) {
            self::Elementary => 'Elementary School (Ages 5-10, K-5th Grade)',
            self::Middle => 'Middle School (Ages 11-13, 6th-8th Grade)',
            self::High => 'High School (Ages 14-18, 9th-12th Grade)',
        };
    }

    public function contentGuidelines(): string
    {
        return match ($this) {
            self::Elementary => <<<'GUIDELINES'
**Target Age: 5-10 years (Elementary School, K-5th Grade)**

VOCABULARY:
- Use simple, concrete words (1-2 syllables preferred for younger students)
- Define any new word immediately with a simple explanation
- Introduce 2-5 new vocabulary words per lesson, scaled by grade level
- Use familiar synonyms when introducing new words
- Build on words they already know

SENTENCE STRUCTURE:
- Short to moderate sentences (5-15 words)
- Simple and compound sentences (and, but, so)
- Keep paragraphs short (3-4 sentences)
- One main idea per paragraph with clear transitions
- Use repetition for key concepts

EXAMPLES & CONTEXT:
- Family, home, school, friends, neighborhood
- Nature, animals, seasons, weather
- Basic colors, shapes, numbers for younger students
- Sports, hobbies, community roles for older students
- Simple cause and effect, basic science observations

ACTIVITIES:
- Drawing, labeling, tracing, coloring
- Matching games and simple experiments
- Story completion and partner activities
- Physical movement activities
- Show and tell, group discussion questions

TONE:
- Warm, encouraging, and curious
- Use "we" and "let's" language
- Frame learning as discovery
- Celebrate small discoveries and praise effort
- Keep everything fun and engaging
GUIDELINES,

            self::Middle => <<<'GUIDELINES'
**Target Age: 11-13 years (Middle School, 6th-8th Grade)**

VOCABULARY:
- Academic and subject-specific terms
- Expect readers to use context clues
- Introduce Greek and Latin roots
- Build connections between related terms
- 4-6 new vocabulary words per lesson

SENTENCE STRUCTURE:
- Complex and compound-complex sentences
- Varied paragraph lengths with clear organization
- Use transition words and phrases
- Extended arguments and explanations
- Citation and evidence integration

EXAMPLES & CONTEXT:
- Current events (age-appropriate)
- Historical connections and career exploration
- Global perspectives and social responsibility
- Scientific discoveries and debates
- Economic and political concepts at introductory level

ACTIVITIES:
- Research projects with multiple sources
- Presentations, demonstrations, and debates
- Analytical writing and peer review
- Real-world problem solving
- Original research and collaboration projects

TONE:
- Treat as capable young scholars
- Encourage critical thinking
- Present multiple perspectives
- Challenge assumptions respectfully
- Foster intellectual confidence and independence
GUIDELINES,

            self::High => <<<'GUIDELINES'
**Target Age: 14-18 years (High School, 9th-12th Grade)**

VOCABULARY:
- Advanced academic and professional vocabulary
- Discipline-specific jargon and technical terms
- SAT/ACT level vocabulary introduction
- Abstract concepts, theories, and interdisciplinary connections
- Etymology and nuanced word meanings

SENTENCE STRUCTURE:
- College-level prose with sophisticated variety
- Extended analytical paragraphs
- Complex argumentative structures
- Integration of sources, evidence, and varied rhetorical strategies
- Professional communication standards

EXAMPLES & CONTEXT:
- Career and college connections
- Complex social, ethical, and philosophical issues
- Advanced scientific concepts and research methodology
- Economic and global systems
- Industry and professional applications

ACTIVITIES:
- Extended essays, research papers, and case studies
- Laboratory and field investigations
- Independent project design and thesis development
- Professional-level projects and peer collaboration
- Real-world implementation and presentation defense

TONE:
- Collegial, professional, and challenging
- Assume intellectual maturity
- Encourage original thinking and academic voice
- Push beyond surface understanding
- Treat as emerging professionals with intellectual rigor
GUIDELINES,
        };
    }

    public static function fromValue(string $value): ?self
    {
        foreach (self::cases() as $case) {
            if ($case->value === $value) {
                return $case;
            }
        }

        return null;
    }

    public static function fromMinMaxAge(int $minAge, int $maxAge): ?self
    {
        foreach (self::cases() as $case) {
            if ($case->minAge() === $minAge && $case->maxAge() === $maxAge) {
                return $case;
            }
        }

        return null;
    }

    public static function toSelectArray(): array
    {
        return array_map(fn (self $case) => [
            'value' => $case->value,
            'label' => $case->label(),
            'min_age' => $case->minAge(),
            'max_age' => $case->maxAge(),
        ], self::cases());
    }
}
