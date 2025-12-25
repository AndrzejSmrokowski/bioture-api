# Context for Biology Exam JSON Generator

This document describes the schema required to generate a valid JSON fixture for the Bioture Biology Exam system.

## 1. Target JSON Structure
The goal is to produce a JSON file (e.g., `biologia-2025.json`) with the following structure:

```json
{
  "examId": "biologia-2025-maj-rozszerzona",
  "year": 2025,
  "month": 5,                     // See [Enum: Month]
  "type": "matura",               // See [Enum: ExamType]
  "source": { "pdf": "filename.pdf" },
  "taskGroups": [
    {
      "number": 2,                // Main task number
      "stimulus": "<p>Content HTML...</p>", // Shared HTML stimulus (sanitized, no style tags)
      "examPage": 2,              // Page number in PDF
      "items": [
        {
          "code": "2.1",          // ALWAYS string. Format: "{TaskNumber}.{SubNumber}" or "{TaskNumber}" if single item.
          "type": "short_open",   // See [Enum: TaskType]
          "answerFormat": "text", // See [Enum: AnswerFormat]
          "maxPoints": 1,
          "prompt": "<p>Question HTML...</p>",
          "options": null,        // See [Options Formats]
          "answerKey": [],        // See [AnswerKey Contracts]
          "gradingRubric": null,  // Optional string/JSON rubric
          "tags": ["genetics"],   // Optional tags/sections
        }
      ]
    }
  ]
}
```

## 2. STRICT Rules

### Code Format
- **ALWAYS** a string.
- Format: `"{TaskNumber}.{SubNumber}"` (e.g., "1.1", "1.2").
- If a task has no sub-items, use `"{TaskNumber}"` (e.g., "2") OR `"{TaskNumber}.0"` ("2.0") but be consistent within file.
- `code` must be unique within the exam.

### Stimulus & Prompts
- Must be **HTML strings**.
- **Sanitized**: Use `<p>`, `<ul>`, `<li>`, `<strong>`, `<em>`, `<table>`, `<tr>`, `<td>`.
- **NO**: `<img>` (use Asset entity logic later), embedded `<style>`, `<div>` (unless essential).

## 3. AnswerKey Contracts (Per Format)

Define `answerKey` as an array of objects obeying these contracts:

### Text / Short Open
```json
"answerKey": [
  { "type": "contains_all", "tokens": ["mitochondrium", "ATP"] },
  { "type": "exact_match", "value": "cykl Krebsa" } // Alternative correct answer
]
```

### Number / Calculation
```json
"answerKey": [
  { "type": "number", "value": 12.5, "tolerance": 0.1, "unit": "mmol" }
]
```

### Choice (Single/Multi)
```json
"answerKey": [
  { "id": "A" } // For single choice
  // OR
  { "id": "A" }, { "id": "C" } // For multi choice correct combination
]
```

### True/False
```json
"answerKey": [
  { "id": "1", "value": true },
  { "id": "2", "value": false }
]
```

### Table Filling
```json
"answerKey": [
  { "type": "table", "cells": { "r1c1": "Value A", "r1c2": "Value B" } }
]
```

## 4. Options Formats (Per Type)

### Choice / Multi-Choice
```json
"options": {
  "choices": [
    { "id": "A", "text": "Answer A text" },
    { "id": "B", "text": "Answer B text" }
  ]
}
```

### True/False statements
```json
"options": {
  "statements": [
    { "id": "1", "text": "Mitochondrium to centrum energetyczne." },
    { "id": "2", "text": "Rybosomy trawią białka." }
  ]
}
```

### Matching
```json
"options": {
  "left": [{ "id": "L1", "text": "..." }],
  "right": [{ "id": "R1", "text": "..." }]
}
```

## 5. Entity Details (Reference)

### TaskGroup
- `number` (int): Main number.
- `stimulus` (HTML): Shared text.
- `keyPage` (int): Page in key PDF (optional).

### TaskItem
- `code` (string): Unique identifier.
- `prompt` (HTML): Question text.
- `gradingRubric` (string/json): Guide for grader.
- `sampleSolutions` (json): Example full answers.
- `commonPitfalls` (json): Common mistakes.
