<?php

namespace Bioture\Story;

use Bioture\Exam\Domain\Model\Enum\AnswerFormat;
use Bioture\Exam\Domain\Model\Enum\ExamType;
use Bioture\Exam\Domain\Model\Enum\Month;
use Bioture\Exam\Domain\Model\Enum\TaskType;
use Bioture\Exam\Infrastructure\Factory\ExamFactory;
use Bioture\Exam\Infrastructure\Factory\TaskGroupFactory;
use Bioture\Exam\Infrastructure\Factory\TaskItemFactory;
use Zenstruck\Foundry\Attribute\AsFixture;
use Zenstruck\Foundry\Story;

#[AsFixture(name: 'biology_2025')]
final class BiologyExamStory extends Story
{
    public function build(): void
    {
        $jsonContent = file_get_contents(__DIR__ . '/../../data/exam/biologia-2025.json');
        
        if ($jsonContent === false) {
            throw new \RuntimeException('Could not read exam data file.');
        }

        $data = json_decode($jsonContent, true, 512, JSON_THROW_ON_ERROR);

        // Create the Exam
        $exam = ExamFactory::createOne([
            'examId' => $data['examId'],
            'year' => $data['year'],
            'month' => Month::tryFrom($data['month']) ?? Month::MAY,
            'type' => ExamType::tryFrom($data['type']) ?? ExamType::MATURA,
        ]);

        // Create Task Groups (Zadania)
        foreach ($data['taskGroups'] as $groupData) {
            $group = TaskGroupFactory::createOne([
                'exam' => $exam,
                'number' => $groupData['number'],
                'stimulus' => $groupData['stimulus'] ?? null,
                'examPage' => $groupData['examPage'] ?? null,
            ]);

            // Create Task items (Podpunkty) inside the group
            if (isset($groupData['items'])) {
                foreach ($groupData['items'] as $itemData) {
                     TaskItemFactory::createOne([
                        'group' => $group,
                        'code' => $itemData['code'] ?? ($groupData['number'] . '.' . ($itemData['subNumber'] ?? '1')),
                        'type' => TaskType::tryFrom($itemData['type']) ?? TaskType::SHORT_OPEN,
                        'answerFormat' => AnswerFormat::tryFrom($itemData['answerFormat']) ?? AnswerFormat::TEXT,
                        'maxPoints' => $itemData['maxPoints'],
                        'prompt' => $itemData['prompt'],
                        'options' => $itemData['options'] ?? null,
                        'answerKey' => $itemData['answerKey'] ?? [],
                    ]);
                }
            }
        }
    }
}
