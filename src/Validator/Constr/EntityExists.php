<?php

namespace App\Validator\Constr;

use Symfony\Component\Validator\Constraint;

#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
class EntityExists extends Constraint
{
    public string $message = 'Запись с ID "{{ value }}" в таблице {{ entity }} не найдена.';

    public function __construct(
        public string $entityClass, 
        ?array $groups = null,
        mixed $payload = null,
        ?string $message = null,
    ) {
        parent::__construct([], $groups, $payload);
        
        $this->message = $message ?? $this->message;
    }

    public function getRequiredOptions(): array
    {
        return ['entityClass'];
    }
}