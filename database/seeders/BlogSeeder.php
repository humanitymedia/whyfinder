<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\PostCategory;
use App\Models\PostTag;
use App\Models\User;
use Illuminate\Database\Seeder;

class BlogSeeder extends Seeder
{
    public function run(): void
    {
        // Categories
        $categories = collect([
            ['name' => 'Finding Purpose', 'description' => 'Articles about discovering and living your why.'],
            ['name' => 'Personal Growth', 'description' => 'Tools and strategies for continuous self-improvement.'],
            ['name' => 'Business Building', 'description' => 'Building a purpose-driven business from the ground up.'],
            ['name' => 'Mindset', 'description' => 'Developing the mental frameworks for success.'],
        ])->map(fn ($cat) => PostCategory::firstOrCreate(['name' => $cat['name']], $cat));

        // Tags
        $tags = collect(['Purpose', 'Motivation', 'Career', 'Mindset', 'Growth'])
            ->map(fn ($name) => PostTag::firstOrCreate(['name' => $name]));

        // Author — use admin account
        $author = User::where('email', 'bear@humanitymedia.net')->first()
            ?? User::first();

        if (! $author) {
            return;
        }

        // Posts
        $posts = [
            [
                'title' => 'Why Most People Never Find Their Purpose (And How You Can)',
                'excerpt' => 'Purpose isn\'t something you stumble upon — it\'s something you build. Here\'s the framework that changed everything for me.',
                'body' => $this->post1Body(),
                'post_category_id' => $categories->firstWhere('name', 'Finding Purpose')->id,
                'status' => 'published',
                'is_featured' => true,
                'tag_names' => ['Purpose', 'Growth'],
            ],
            [
                'title' => 'The 5 Questions That Reveal Your Core Values',
                'excerpt' => 'Your values are the compass that guides every decision. These five questions will help you uncover what truly matters.',
                'body' => $this->post2Body(),
                'post_category_id' => $categories->firstWhere('name', 'Personal Growth')->id,
                'status' => 'published',
                'is_featured' => false,
                'tag_names' => ['Purpose', 'Mindset'],
            ],
            [
                'title' => 'Building a Business Around Your Why',
                'excerpt' => 'When your business is rooted in purpose, profit follows. Here\'s how to align your entrepreneurial journey with your deepest values.',
                'body' => $this->post3Body(),
                'post_category_id' => $categories->firstWhere('name', 'Business Building')->id,
                'status' => 'published',
                'is_featured' => false,
                'tag_names' => ['Career', 'Purpose'],
            ],
            [
                'title' => 'From Burnout to Breakthrough: A Mindset Shift',
                'excerpt' => 'Burnout isn\'t just about working too hard — it\'s about working without meaning. Here\'s how to flip the script.',
                'body' => $this->post4Body(),
                'post_category_id' => $categories->firstWhere('name', 'Mindset')->id,
                'status' => 'published',
                'is_featured' => false,
                'tag_names' => ['Mindset', 'Motivation'],
            ],
            [
                'title' => 'How to Stay Motivated When the Path Gets Hard',
                'excerpt' => 'Motivation fades. Purpose doesn\'t. Learn the difference and build systems that sustain you through the hard seasons.',
                'body' => $this->post5Body(),
                'post_category_id' => $categories->firstWhere('name', 'Personal Growth')->id,
                'status' => 'draft',
                'is_featured' => false,
                'tag_names' => ['Motivation', 'Growth'],
            ],
        ];

        foreach ($posts as $i => $data) {
            $tagNames = $data['tag_names'];
            unset($data['tag_names']);

            $wordCount = str_word_count(strip_tags($data['body']));
            $data['read_time_minutes'] = max(1, (int) ceil($wordCount / 200));
            $data['author_id'] = $author->id;
            $data['published_at'] = $data['status'] === 'published'
                ? now()->subDays(count($posts) - $i)
                : null;

            $post = Post::firstOrCreate(
                ['title' => $data['title']],
                $data
            );

            $tagIds = $tags->filter(fn ($t) => in_array($t->name, $tagNames))->pluck('id');
            $post->tags()->syncWithoutDetaching($tagIds);
        }
    }

    private function post1Body(): string
    {
        return <<<'MD'
## The Purpose Myth

There's a pervasive myth in our culture that purpose is something you *discover* — like finding buried treasure on some distant island. You wake up one morning, the clouds part, and suddenly you just *know* what you were put on this earth to do.

That's not how it works.

Purpose is built, not found. It's the product of self-awareness, experimentation, and intentional reflection. And the good news? Anyone can build it.

## Why We Get Stuck

Most people never find their purpose because they're asking the wrong question. They ask, "What is my purpose?" as if it's a single, fixed answer waiting to be revealed. A better question is: **"What problems am I uniquely equipped to solve?"**

This shift changes everything. Instead of waiting for inspiration, you start looking at the intersection of three things:

1. **What you're good at** — your skills, talents, and strengths
2. **What you care about** — the issues that keep you up at night
3. **What the world needs** — the gaps you can fill

## The Framework

Here's a simple exercise to get started:

- **List your top 5 skills.** Not just job skills — life skills. Are you a great listener? A natural teacher? An analytical thinker?
- **Name 3 problems that frustrate you.** What do you wish someone would fix? Education access? Mental health stigma? Small business failure rates?
- **Find the overlap.** Where do your skills meet the problems you care about? That intersection is your purpose zone.

## Start Small

You don't need to quit your job tomorrow. Purpose can show up in how you mentor a colleague, how you volunteer on weekends, or how you approach your daily work with a new lens.

The key is to start. Purpose reveals itself through action, not contemplation.

---

*Ready to go deeper? Our free foundational course walks you through this framework step by step.*
MD;
    }

    private function post2Body(): string
    {
        return <<<'MD'
## Values: Your Internal Compass

Your core values are the non-negotiable principles that guide your decisions, shape your relationships, and define who you are at your best. When you live in alignment with your values, everything feels right. When you don't, something always feels off.

But here's the problem: most people have never consciously identified their values. They operate on autopilot, driven by societal expectations rather than internal truth.

## The 5 Questions

Grab a journal and give yourself 20 minutes. Answer these honestly:

### 1. When have you felt most alive?

Think about a specific moment — not a vacation or a celebration, but a moment where you felt deeply engaged and fulfilled. What were you doing? Who were you with? What values were being honored in that moment?

### 2. What makes you angry?

Anger is a powerful signal. It often points to a value being violated. If injustice makes you angry, fairness might be a core value. If dishonesty infuriates you, integrity likely matters deeply.

### 3. What would you do if money weren't a factor?

This question strips away external motivators and reveals intrinsic ones. Your answer points to what you value for its own sake.

### 4. Who do you admire, and why?

The qualities you admire in others are often the values you hold most dear. Make a list of people you look up to and identify the specific traits that draw you to them.

### 5. What are you willing to sacrifice for?

True values are things you'll protect even when it's costly. If you'd take a pay cut for more family time, family is a genuine value — not just something you say matters.

## Putting It Together

Look for patterns across your answers. You'll likely see 3-5 themes emerge. These are your core values. Write them down. Put them somewhere visible. Let them guide your next decision.
MD;
    }

    private function post3Body(): string
    {
        return <<<'MD'
## Purpose-Driven Business

The most resilient businesses aren't built on market trends or investor whims — they're built on deep conviction. When your business exists to solve a problem you genuinely care about, you gain an unfair advantage: you'll keep going when others quit.

## Start With Why (Literally)

Before you write a business plan, write a purpose statement. Answer these:

- **Who do you serve?** Be specific. Not "everyone" — who specifically benefits from what you do?
- **What pain do you relieve?** What frustration, challenge, or gap exists in their lives?
- **Why does it matter to you?** What personal connection do you have to this problem?

Your purpose statement might look like: *"I help first-generation entrepreneurs build sustainable businesses because I watched my parents struggle without guidance, and I believe everyone deserves a fair shot."*

## Aligning Profit and Purpose

Here's the truth: purpose and profit aren't opposites. Purpose *drives* profit because it creates:

- **Authentic marketing** — You don't need gimmicks when you genuinely believe in your mission
- **Customer loyalty** — People support businesses that stand for something
- **Team alignment** — Employees who share your mission bring their best work

## The Practical Steps

1. **Validate your idea** with the people you want to serve. Talk to them. Listen deeply.
2. **Start with a minimum viable offering.** Don't wait for perfection — start helping people now.
3. **Measure impact alongside revenue.** Track how many lives you're touching, not just dollars.
4. **Tell your story.** People connect with founders who have a genuine why behind their work.

## The Long Game

Building a purpose-driven business is a marathon, not a sprint. There will be seasons of doubt. But when your why is clear, you'll find the resilience to push through.
MD;
    }

    private function post4Body(): string
    {
        return <<<'MD'
## Redefining Burnout

We've been thinking about burnout all wrong. It's not simply the result of working too many hours. Plenty of people work incredibly hard and feel energized. Burnout happens when there's a disconnect between effort and meaning.

When you can't answer the question "Why am I doing this?" — that's when exhaustion sets in.

## The Warning Signs

Burnout doesn't arrive overnight. Watch for these signals:

- **Cynicism** about work you used to enjoy
- **Emotional exhaustion** that sleep doesn't fix
- **Reduced effectiveness** despite putting in more hours
- **Detachment** from colleagues, clients, or the mission

If you're nodding along, you're not broken. You're misaligned.

## The Mindset Shift

The breakthrough comes from shifting your focus from **output** to **impact**. Instead of asking "How much did I get done today?" ask:

- **"Did my work matter to someone today?"**
- **"Am I using my strengths or fighting against them?"**
- **"Is this season of effort moving me toward or away from my values?"**

## Rebuilding With Purpose

Here's a practical recovery path:

### Step 1: Audit Your Energy
For one week, track what gives you energy and what drains it. You'll see patterns. Burnout usually comes from spending too much time in the drain column.

### Step 2: Reclaim One Hour
Take back one hour per day for work that aligns with your values. Protect it fiercely.

### Step 3: Set Boundaries
Say no to one thing this week that doesn't serve your purpose. The world won't end.

### Step 4: Reconnect With Your Why
Revisit the reason you started. If that reason has changed, that's okay — update it.

## Moving Forward

Burnout is not a dead end. It's a signal that something needs to change. Listen to it.
MD;
    }

    private function post5Body(): string
    {
        return <<<'MD'
## The Motivation Problem

Here's an uncomfortable truth: motivation is unreliable. It's a feeling — and like all feelings, it comes and goes. If your plan depends on feeling motivated every day, your plan will fail.

But purpose? Purpose is a decision. It doesn't depend on how you feel on a Tuesday morning. It's the anchor that holds when motivation drifts away.

## Why The Path Gets Hard

Every meaningful pursuit has what I call the **"messy middle"** — that stretch between the excitement of starting and the satisfaction of finishing. In the messy middle:

- Initial enthusiasm fades
- Results haven't arrived yet
- Comparison steals your confidence
- Doubts multiply

This is where most people quit. But it's also where the most growth happens.

## Building Sustainable Systems

Instead of relying on motivation, build systems:

### 1. Identity-Based Habits
Don't say "I want to write a book." Say "I am a writer." When your identity shifts, your behavior follows. Writers write — even on days they don't feel like it.

### 2. Environment Design
Make the right thing easy and the wrong thing hard. Want to exercise? Sleep in your workout clothes. Want to write? Close all browser tabs except your document.

### 3. Accountability Partners
Find someone on a similar journey. Not someone who lets you off the hook — someone who lovingly holds you to your word.

### 4. Progress Tracking
What gets measured gets managed. Track your small wins. They compound faster than you think.

## The Purpose Advantage

When your actions are connected to something bigger than yourself, discipline becomes easier. You're not just grinding — you're building something that matters.

The path will get hard. That's not a bug — it's a feature. Hard things are worth doing when they're connected to your why.
MD;
    }
}
