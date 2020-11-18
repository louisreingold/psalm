<?php

/* PSALM HAS BEEN MODIFIED TO INCLUDE THIS FILE IN PSALM.PHP RIGHT BEFORE RUNNING THE PROJECT ANALYZER */

/* LOAD THE GLOBAL TYPES */

// $GLOBAL_TYPE_COMMENT = generate_global_type_comment(
//     array_merge(
//         glob("../plugin/psalm-types/*.php"),
//         glob("../plugin/psalm-types/*/*.php"),
//     )
// );

$GLOBAL_TYPES_STRING = generate_global_types_string(
    array_merge(
        glob("../plugin/psalm-types/*.php"),
        glob("../plugin/psalm-types/*/*.php"),
    )
);

// make is a fuckin g
$GLOBAL_TYPES_STRING_LINE_LENGTH = substr_count($GLOBAL_TYPES_STRING, "\n") + 4;

function generate_global_types_string($filenames)
{
    return array_reduce($filenames, function ($acc, $item) {
        return $acc . "\n\n" . str_replace("?>", "", str_replace("<?php", "", file_get_contents($item)));
    });
}

/**
 * @param string[] $files_to_parse
 * @return IDontKNow
 */
function generate_global_type_comment($filenames)
{

    $global_types_string = generate_global_types_string($filenames);

    $global_comment = new \PhpParser\Comment\Doc(
        $global_types_string
        ,
        -1,
        -1);

    $parsed_comment = \Psalm\DocComment::parsePreservingLength($global_comment);

    // var_dump($parsed_comment);

    return $parsed_comment;

}

/*
JACK THE GLOBAL TYPES INTO PSALM
Psalm has been modified in CommentAnalyzer.php getTypeAliasesFromComment() to call this function
 */

/**
 * @param array<string, TypeAlias\InlineTypeAlias> $t
 * @return array<string, TypeAlias\InlineTypeAlias>
 * i have no idea what $aliases, $type_aliases, and $self_fqcln are, but
 * CommentAnalyzer::getTypeAliasesFromCommentLines wants them
 */

function louis_jack_in_the_global_types($t, $aliases, $type_aliases, $self_fqcln)
{

    global $GLOBAL_TYPE_COMMENT;

    return array_merge(\Psalm\Internal\Analyzer\CommentAnalyzer::getTypeAliasesFromCommentLines(
        $GLOBAL_TYPE_COMMENT->tags['psalm-type'],
        $aliases,
        $type_aliases,
        $self_fqcln
    ), $t);

}
