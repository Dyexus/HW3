<?php

const CARD_TYPES = ['Retail', 'Dining', 'Entertainment'];

function escape($value): string
{
    return htmlspecialchars(
        (string) $value,
        ENT_QUOTES | ENT_SUBSTITUTE,
        'UTF-8'
    );
}

function validateCardId($value): ?int
{
    if (!is_string($value) && !is_int($value)) {
        return null;
    }

    $id = filter_var($value, FILTER_VALIDATE_INT, [
        'options' => ['min_range' => 1]
    ]);

    return $id === false ? null : $id;
}

function validateGiftCard(array $input): array
{
    $errors = [];
    $data = [];

    foreach (['cardName', 'cardType', 'cardValue', 'points'] as $field) {
        $value = $input[$field] ?? '';
        $data[$field] = is_string($value) ? trim($value) : '';
    }

    if ($data['cardName'] === '' || strlen($data['cardName']) > 100) {
        $errors['cardName'] = 'Enter a card name of 1–100 bytes.';
    }

    if (!in_array($data['cardType'], CARD_TYPES, true)) {
        $errors['cardType'] = 'Select a valid card type.';
    }

    if (
        !is_numeric($data['cardValue']) ||
        !is_finite((float) $data['cardValue']) ||
        (float) $data['cardValue'] <= 0
    ) {
        $errors['cardValue'] = 'Card value must be a positive number.';
    }

    $points = filter_var($data['points'], FILTER_VALIDATE_INT, [
        'options' => ['min_range' => 1]
    ]);

    if ($points === false) {
        $errors['points'] = 'Points required must be a positive whole number.';
    }

    return ['data' => $data, 'errors' => $errors];
}