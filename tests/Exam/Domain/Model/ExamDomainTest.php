<?php

namespace Bioture\Tests\Exam\Domain\Model;

use Bioture\Exam\Domain\Model\Enum\AnswerFormat;
use Bioture\Exam\Domain\Model\Enum\ExamAttemptStatus;
use Bioture\Exam\Domain\Model\Enum\ExamType;
use Bioture\Exam\Domain\Model\Enum\GraderType;
use Bioture\Exam\Domain\Model\Enum\Month;
use Bioture\Exam\Domain\Model\Enum\TaskType;
use Bioture\Exam\Domain\Model\Exam;
use Bioture\Exam\Domain\Model\ExamAttempt;
use Bioture\Exam\Domain\Model\TaskEvaluation;
use Bioture\Exam\Domain\Model\TaskGroup;
use Bioture\Exam\Domain\Model\TaskItem;
use Bioture\Exam\Domain\Model\ValueObject\GradingSpec;
use Bioture\Exam\Domain\Model\ValueObject\RubricRule;
use Bioture\Exam\Domain\Model\ValueObject\TaskCode;
use PHPUnit\Framework\TestCase;

class ExamDomainTest extends TestCase
{
    public function testTaskCodeValidation(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid task code format');
        new TaskCode('Invalid Code!');
    }

    public function testTaskCodeEquality(): void
    {
        $code1 = new TaskCode('1.1');
        $code2 = new TaskCode('1.1');
        $code3 = new TaskCode('1.2');

        $this->assertTrue($code1->equals($code2));
        $this->assertFalse($code1->equals($code3));
    }

    public function testGradingSpecValidation(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid grading type');
        new GradingSpec('invalid_type', 10);
    }

    public function testExamWorkflow(): void
    {
        // 1. Setup Exam Structure
        $exam = new Exam('E-2025-MAY', 2025, Month::MAY, ExamType::MATURA);
        $group = new TaskGroup($exam, 1);

        $spec = new GradingSpec(
            GradingSpec::TYPE_RUBRIC,
            5,
            [new RubricRule(5, 'Perfect')]
        );

        $taskItem = new TaskItem(
            $group,
            new TaskCode('1.1'),
            TaskType::SHORT_OPEN,
            AnswerFormat::TEXT,
            $spec,
            'Describe X'
        );

        // 2. Start Attempt
        $attempt = new ExamAttempt($exam, 'user-123');
        $this->assertSame(ExamAttemptStatus::STARTED, $attempt->getStatus());

        // 3. Record Answer
        $answer = $attempt->recordAnswer($taskItem, 'My Answer');
        $this->assertTrue($answer->getTaskCode()->equals($taskItem->getCode()));
        $this->assertCount(1, $attempt->getAnswers());

        // 4. Submit
        $attempt->submit();
        $this->assertSame(ExamAttemptStatus::SUBMITTED, $attempt->getStatus());
        $this->assertNotNull($attempt->getSubmittedAt());

        // 5. Grade
        // Create evaluation
        $evaluation = TaskEvaluation::createManual(
            $attempt,
            $taskItem,
            3,
            'Teacher'
        );
        $attempt->recordEvaluation($evaluation);
        $attempt->finishGrading();

        $this->assertSame(ExamAttemptStatus::GRADED, $attempt->getStatus());

        // 6. Try to Submit again (should fail)
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Cannot submit an already graded exam');
        $attempt->submit();
    }

    public function testEvaluationPointsValidation(): void
    {
        $exam = new Exam('E-MAX', 2025, Month::MAY, ExamType::MATURA);
        $group = new TaskGroup($exam, 1);
        $spec = new GradingSpec(GradingSpec::TYPE_DETERMINISTIC, 2);
        $taskItem = new TaskItem(
            $group,
            new TaskCode('2'),
            TaskType::SINGLE_CHOICE,
            AnswerFormat::CHOICE,
            $spec,
            'Choose A'
        );

        $attempt = new ExamAttempt($exam, 'user-1');

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('cannot exceed max points (2)');

        TaskEvaluation::createAi($attempt, $taskItem, 3, 'v1');
    }
}
