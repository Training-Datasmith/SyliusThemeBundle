<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Sylius Sp. z o.o.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare (strict_types=1);
namespace Sylius\Bundle\Theme_Bundle\Factory;

use Sylius\Bundle\Theme_Bundle\Model\Theme_Author;
final class Theme_Author_Factory implements Theme_Author_Factory_Interface
{
    public function create_from_array(array $data): Theme_Author
    {
        /** @var ThemeAuthor $author */
        $author = new Theme_Author();
        $author->set_name($data['name'] ?? null);
        $author->set_email($data['email'] ?? null);
        $author->set_homepage($data['homepage'] ?? null);
        $author->set_role($data['role'] ?? null);
        return $author;
    }
}