<?php
namespace Omocha;

abstract class Filter {
	const TYPE_NULL        =   2;
	const TYPE_STRING      =   4;
	const TYPE_INTEGER     =   8;
	const TYPE_FLOAT       =  16;
	const TYPE_BOOLEAN     =  32;
	const TYPE_ARRAY       =  64;
	const TYPE_OBJECT      = 128;
	const TYPE_ALL         = 254;
	const HAS_ARGUMENT     = 256;
	const NOT_HAS_ARGUMENT = 512;
}
?>