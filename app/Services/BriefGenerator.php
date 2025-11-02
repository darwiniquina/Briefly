<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class BriefGenerator
{
    public function generate(string $rawInput, string $type): string
    {
        try {
            $rawInput = trim($rawInput);

            $validationPrompt = [
                [
                    'role' => 'system',
                    'content' => "
You are a content validator for a creative brief generation tool.

Your goal is to decide if the user's input contains any meaningful information
that could help create a project brief — even if it's casual, messy, or incomplete.

Return only one word:
- 'valid' if it includes any details about goals, audience, branding, products, services, or creative direction.
- 'invalid' only if it is completely random, off-topic, spammy, or contains no project-related information at all.

Be generous — conversational or partial project descriptions still count as valid.
",
                ],
                [
                    'role' => 'user',
                    'content' => "User input: {$rawInput}",
                ],
            ];

            $validationResponse = Http::withHeaders([
                'Authorization' => env('AI_KEY'),
                'Content-Type' => 'application/json',
            ])->post('https://llm-gateway.assemblyai.com/v1/chat/completions', [
                'model' => 'gpt-4.1',
                'messages' => $validationPrompt,
                'temperature' => 0.2,
                'max_tokens' => 50,
            ]);

            $judgment = strtolower(trim($validationResponse->json('choices.0.message.content') ?? ''));

            if ($judgment !== 'valid') {
                return 'Sorry — your input doesn’t look like a creative brief or project request. Try adding context such as project goals, audience, deliverables, or timeline.';
            }

            $messages = [
                [
                    'role' => 'system',
                    'content' => '
You are a seasoned **Creative Brief Writer** and **Strategic Consultant** who transforms messy client notes into professional, actionable creative briefs.

### Your Mission
Convert the provided unstructured input into a refined, comprehensive project brief that a design, marketing, or development team could immediately execute.

### Output Format (Required)
- Write the entire response in **clean, valid Markdown**.
- Use **hierarchical headings** (e.g., `#`, `##`, `###`) for logical structure.
- Use **bullet points** or **short paragraphs** for readability.
- No tables or code blocks.
- Do **not** include commentary, disclaimers, or markdown backticks.

### Writing Style
- Tone: **Professional**, **strategic**, and **concise**, yet **inspiring**.
- Language: Use plain English with clarity and intent.
- Avoid buzzwords, filler, or marketing clichés.

### Required Sections
Each section must be included (adapt details to the project type):
1. **Project Title**
2. **Project Overview / Context**
3. **Goals & Objectives**
4. **Target Audience**
5. **Key Message / Proposition**
6. **Deliverables & Specifications**
7. **Timeline & Budget**
8. **Mandatories & Restrictions**

If some details are missing, **infer logical placeholders** based on industry norms (and clearly label them as inferred).
',
                ],
                [
                    'role' => 'user',
                    'content' => "Analyze the following raw client input and generate a complete, well-structured **{$type}** project brief based on the above instructions.### Raw Client Input: {$rawInput}",
                ],
            ];

            $response = Http::withHeaders([
                'Authorization' => env('AI_KEY'),
                'Content-Type' => 'application/json',
            ])->post('https://llm-gateway.assemblyai.com/v1/chat/completions', [
                'model' => 'gpt-4.1',
                'messages' => $messages,
                'temperature' => 0.5,
                'max_tokens' => 800,
            ]);

            $content = $response->json('choices.0.message.content');

            return $content;

        } catch (\Throwable $e) {
            return "Error: {$e->getMessage()}";
        }
    }
}
