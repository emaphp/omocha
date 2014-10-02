<?php
namespace Omocha;

abstract class Filter {
	const TYPE_STRING      =   2;
	const TYPE_INTEGER     =   4;
	const TYPE_FLOAT       =   8;
	const TYPE_BOOLEAN     =  16;
	const TYPE_ARRAY       =  32;
	const TYPE_OBJECT      =  64;
	const TYPE_ALL         = 126;
	const HAS_ARGUMENT     = 128;
	const NOT_HAS_ARGUMENT = 256;
}
?>