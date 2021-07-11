<?php
declare(strict_types = 1);

namespace App\Enum;

/**
 * Class UserRoleEnum
 */
final class UserRoleEnum extends AbstractEnum
{
	const USER = 'ROLE_USER';
	const ADMIN = 'ROLE_ADMIN';
	const VISITOR = 'ROLE_VISITOR';
}
