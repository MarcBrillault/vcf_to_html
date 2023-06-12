<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Metadata;
use App\vCard;
use Twig\Loader\FilesystemLoader;
use \Twig\Environment;

$file = __DIR__ . "/vcards.vcf";



$vcardData = splitVcardData($file);

$cards = getVcardsFromArray($vcardData);
usort($cards, function ($a, $b) {
    return $a->getLastName() > $b->getLastName();
});

$sortedCards = [];
foreach ($cards as $card) {
    $firstLetter = mb_strtoupper(substr($card->getLastName(), 0, 1));
    if (preg_match('/^[0-9]$/', $firstLetter)) {
        $firstLetter = '0-9';
    }
    $sortedCards[$firstLetter][] = $card;
}

$loader = new FilesystemLoader(__DIR__ . '/templates');
$twig = new Environment($loader);

echo $twig->render('vcf.html.twig', ['cards' => $cards, 'sorted_cards' => $sortedCards]);

function splitVcardData(string $file): array
{
    $content = file_get_contents($file);
    return explode("BEGIN:VCARD", $content);
}



/** @return ?string|array */
function getField(string $fieldName, string $data, bool $multiple = false)
{
    $field = [];
    $regex = sprintf('/^%s(?<metadata>;.*)?:(?<data>.*)/', $fieldName);
    $lines = explode(PHP_EOL, $data);
    foreach ($lines as $line) {
        preg_match_all($regex, $line, $matches, PREG_SET_ORDER);
        foreach ($matches as $match) {
            if (empty($match)) {
                continue;
            }

            // var_dump($match);

            $metadata = null;
            if (!empty($match['metadata'])) {
                $metadata = getMetadata($match['metadata']);
            }

            if (!empty($match['data'])) {
                $field[] = getLine($match['data'], $metadata);
            }
        }
    }

    if ($multiple === true) {
        return $field;
    }

    return $field[0] ?? null;
}

function getMetadata(string $contents): Metadata
{
    $metadata = new Metadata();
    $regexModel = "#%s=([^;]+);?#";

    $regexCharset = sprintf($regexModel, 'CHARSET');
    preg_match($regexCharset, $contents, $matches);
    if (!empty($matches)) {
        $metadata->setCharset($matches[1]);
    }

    $regexEncoding = sprintf($regexModel, 'ENCODING');
    preg_match($regexEncoding, $contents, $matches);
    if (!empty($matches)) {
        $metadata->setEncoding($matches[1]);
    }

    if (strpos($contents, 'PREF') !== false) {
        $metadata->setPref(true);
    }

    if (strpos($contents, 'HOME') !== false) {
        $metadata->setHome(true);
    }

    if (strpos($contents, 'CELL') !== false) {
        $metadata->setCellPhone(true);
    }

    return $metadata;
}

function getLine(string $lineContents, ?Metadata $metadata)
{
    if (is_null($metadata)) {
        return $lineContents;
    }

    if ($metadata->getEncoding() === 'QUOTED-PRINTABLE') {
        $lineContents = quoted_printable_decode($lineContents);
    }

    if ($metadata->getHome() === true) {
        $lineContents = '🏠' . $lineContents;
    }

    if ($metadata->getCellPhone() === true) {
        $lineContents = '📱' . $lineContents;
    }

    if ($metadata->getPref() === true) {
        $lineContents = '⭐' . $lineContents;
    }

    return $lineContents;
}

/**
 * @return []vCard
 */
function getVcardsFromArray(array $arr): array
{
    $cards = [];

    foreach ($arr as $data) {
        $card = new vCard();
        $card->setFullName(getFullname($data));
        list($first, $last) = getFirstAndLastNames($data);
        if ($first !== null) {
            $card->setFirstName($first);
        }
        if ($last !== null) {
            $card->setLastName($last);
        }
        $card->setEmails(getEmails($data));
        $card->setTels(getTels($data));


        if (!$card->isEmpty()) {
            $cards[] = $card;
        }
    }


    return $cards;
}

function getFullname(string $data): ?string
{
    return getField('FN', $data);
}

function getEmails(string $data): array
{
    return getField('EMAIL', $data, true);
}

function getTels(string $data): array
{
    return getField('TEL', $data, true);
}

function getFirstAndLastNames(string $data): array
{
    $names = getField('N', $data);
    if ($names === null) {
        return [null, null];
    }
    $names = explode(';', $names);
    return [$names[1] ?? null, $names[0] ?? null];
}
