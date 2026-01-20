<?php

namespace App\Enums;

enum AgeGroup: string
{
    case AGE_5_6 = '5-6';
    case AGE_7_8 = '7-8';
    case AGE_9_10 = '9-10';
    case AGE_11_12 = '11-12';
    case AGE_13_14 = '13-14';
    case AGE_15_16 = '15-16';
    case AGE_17_19 = '17-19';

    public function minAge(): int
    {
        return match ($this) {
            self::AGE_5_6 => 5,
            self::AGE_7_8 => 7,
            self::AGE_9_10 => 9,
            self::AGE_11_12 => 11,
            self::AGE_13_14 => 13,
            self::AGE_15_16 => 15,
            self::AGE_17_19 => 17,
        };
    }

    public function maxAge(): int
    {
        return match ($this) {
            self::AGE_5_6 => 6,
            self::AGE_7_8 => 8,
            self::AGE_9_10 => 10,
            self::AGE_11_12 => 12,
            self::AGE_13_14 => 14,
            self::AGE_15_16 => 16,
            self::AGE_17_19 => 19,
        };
    }

    public function label(): string
    {
        return match ($this) {
            self::AGE_5_6 => 'Ages 5-6 (Kindergarten-1st)',
            self::AGE_7_8 => 'Ages 7-8 (2nd-3rd Grade)',
            self::AGE_9_10 => 'Ages 9-10 (4th-5th Grade)',
            self::AGE_11_12 => 'Ages 11-12 (6th-7th Grade)',
            self::AGE_13_14 => 'Ages 13-14 (8th-9th Grade)',
            self::AGE_15_16 => 'Ages 15-16 (10th-11th Grade)',
            self::AGE_17_19 => 'Ages 17-19 (12th-College Prep)',
        };
    }

    public function contentGuidelines(): string
    {
        return match ($this) {
            self::AGE_5_6 => <<<'GUIDELINES'
**Target Age: 5-6 years (Kindergarten-1st Grade)**

VOCABULARY:
- Use very simple, concrete words (1-2 syllables preferred)
- Define any new word immediately with a simple explanation
- Avoid abstract concepts unless absolutely necessary
- Use picture-friendly language that can be visualized

SENTENCE STRUCTURE:
- Short sentences (5-10 words maximum)
- Simple subject-verb-object patterns
- One idea per sentence
- Use lots of repetition for key concepts

EXAMPLES & CONTEXT:
- Family, home, toys, pets, playground
- Basic colors, shapes, numbers
- Simple cause and effect
- Familiar daily routines

ACTIVITIES:
- Coloring, drawing, tracing
- Matching games
- Simple songs and rhymes
- Physical movement activities
- Show and tell opportunities

TONE:
- Very warm and encouraging
- Use "we" and "let's" language
- Celebrate small discoveries
- Keep everything fun and playful
GUIDELINES,

            self::AGE_7_8 => <<<'GUIDELINES'
**Target Age: 7-8 years (2nd-3rd Grade)**

VOCABULARY:
- Basic vocabulary with new words explained in context
- Introduce 2-3 new vocabulary words per lesson
- Use familiar synonyms when introducing new words
- Build on words they already know

SENTENCE STRUCTURE:
- Sentences can be 10-15 words
- Can use simple compound sentences (and, but, so)
- Keep paragraphs short (3-4 sentences)
- Use clear transitions between ideas

EXAMPLES & CONTEXT:
- School experiences, friends, neighborhood
- Nature, animals, seasons, weather
- Simple community roles (teachers, firefighters)
- Basic science observations

ACTIVITIES:
- Simple drawing and labeling
- Hands-on experiments with supervision
- Story completion
- Partner activities
- Basic research (asking adults)

TONE:
- Encouraging and curious
- Frame learning as discovery
- Praise effort and thinking process
- Use questions to engage thinking
GUIDELINES,

            self::AGE_9_10 => <<<'GUIDELINES'
**Target Age: 9-10 years (4th-5th Grade)**

VOCABULARY:
- Grade-appropriate vocabulary with context clues
- Introduce 4-5 new terms per lesson
- Include some academic vocabulary
- Explain word roots when helpful

SENTENCE STRUCTURE:
- Varied sentence lengths (10-20 words)
- Complex sentences with dependent clauses
- Paragraphs can be 4-6 sentences
- Use topic sentences and supporting details

EXAMPLES & CONTEXT:
- Sports, hobbies, pop culture references
- Community and civic concepts
- Different regions and cultures
- Basic historical events
- Environmental awareness

ACTIVITIES:
- Hands-on projects and simple experiments
- Research using provided resources
- Comparison and categorization tasks
- Creative writing extensions
- Group discussion questions

TONE:
- Respectful of growing independence
- Encourage questioning and curiosity
- Connect to their interests
- Build confidence in their abilities
GUIDELINES,

            self::AGE_11_12 => <<<'GUIDELINES'
**Target Age: 11-12 years (6th-7th Grade)**

VOCABULARY:
- Academic and subject-specific terms
- Expect readers to use context clues
- Introduce Greek and Latin roots
- Build connections between related terms

SENTENCE STRUCTURE:
- Complex and compound-complex sentences
- Varied paragraph lengths
- Clear organizational structures
- Use of transition words and phrases

EXAMPLES & CONTEXT:
- Current events (age-appropriate)
- Historical connections
- Career exploration
- Global perspectives
- Social responsibility

ACTIVITIES:
- Research projects with multiple sources
- Presentations and demonstrations
- Analytical writing
- Debates and discussions
- Real-world problem solving

TONE:
- Treat as capable young scholars
- Encourage critical thinking
- Present multiple perspectives
- Challenge assumptions respectfully
GUIDELINES,

            self::AGE_13_14 => <<<'GUIDELINES'
**Target Age: 13-14 years (8th-9th Grade)**

VOCABULARY:
- Subject-specific terminology expected
- SAT/ACT level vocabulary introduction
- Technical terms with precise definitions
- Encourage dictionary and reference use

SENTENCE STRUCTURE:
- Sophisticated sentence variety
- Academic writing conventions
- Extended arguments and explanations
- Citation and evidence integration

EXAMPLES & CONTEXT:
- Career and college connections
- Current events and social issues
- Historical parallels and lessons
- Scientific discoveries and debates
- Economic and political concepts

ACTIVITIES:
- Analysis and evaluation tasks
- Debate and argumentation
- Original research projects
- Peer review and collaboration
- Real-world application challenges

TONE:
- Professional and academic
- Encourage independence
- Welcome disagreement with evidence
- Foster intellectual confidence
GUIDELINES,

            self::AGE_15_16 => <<<'GUIDELINES'
**Target Age: 15-16 years (10th-11th Grade)**

VOCABULARY:
- Advanced academic vocabulary
- Discipline-specific jargon
- Nuanced word meanings
- Etymology and word evolution

SENTENCE STRUCTURE:
- College-level prose
- Extended analytical paragraphs
- Complex argumentative structures
- Varied rhetorical strategies

EXAMPLES & CONTEXT:
- Industry and professional applications
- College and career preparation
- Complex social and ethical issues
- Advanced scientific concepts
- Economic and global systems

ACTIVITIES:
- Extended essays and research papers
- Case study analysis
- Laboratory and field investigations
- Independent project design
- Presentation and defense of ideas

TONE:
- Collegial and challenging
- Assume intellectual maturity
- Encourage original thinking
- Push beyond surface understanding
GUIDELINES,

            self::AGE_17_19 => <<<'GUIDELINES'
**Target Age: 17-19 years (12th Grade-College Prep)**

VOCABULARY:
- College-level and professional vocabulary
- Specialized terminology without simplification
- Abstract concepts and theories
- Interdisciplinary connections

SENTENCE STRUCTURE:
- University-level academic writing
- Sophisticated argumentation
- Integration of sources and evidence
- Professional communication standards

EXAMPLES & CONTEXT:
- Abstract concepts and theoretical frameworks
- Research methodology and design
- Professional and graduate study preparation
- Complex ethical and philosophical issues
- Global systems and interdependencies

ACTIVITIES:
- Independent research and synthesis
- Thesis development and defense
- Professional-level projects
- Peer collaboration and review
- Real-world implementation

TONE:
- Scholarly and professional
- Treat as emerging professionals
- Expect intellectual rigor
- Foster academic voice and authority
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
