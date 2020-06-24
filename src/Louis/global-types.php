<?php

/* PSALM HAS BEEN MODIFIED TO INCLUDE THIS FILE IN PSALM.PHP RIGHT BEFORE RUNNING THE PROJECT ANALYZER */

/* LOAD THE GLOBAL TYPES */
$GLOBAL_TYPE_COMMENT = generate_global_type_comment(
    [
        '../test/src/types/types.php',
        '../test/src/types/more-types.php'
    ]
);

/**
 * @param string[] $files_to_parse
 * @return IDontKNow
 */
function generate_global_type_comment($filenames) {

    $global_types_string = array_reduce($filenames, function($acc, $item) {
        return $acc . "\n\n" . str_replace("?>", "", str_replace("<?php", "", file_get_contents($item)));
    });

    $global_comment = new \PhpParser\Comment\Doc(
        $global_types_string
        ,
        -1,
        -1);


    return \Psalm\DocComment::parsePreservingLength($global_comment);

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

function louis_jack_in_the_global_types($t, $aliases, $type_aliases, $self_fqcln) {

    global $GLOBAL_TYPE_COMMENT;

    return array_merge(\Psalm\Internal\Analyzer\CommentAnalyzer::getTypeAliasesFromCommentLines(
        $GLOBAL_TYPE_COMMENT->tags['psalm-type'],
        $aliases,
        $type_aliases,
        $self_fqcln
    ), $t);

}

