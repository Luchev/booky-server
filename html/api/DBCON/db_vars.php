<?php

class DBVARS {
  const PREPARED = array(
    'log_query' => [
      'sql' => 'INSERT INTO query_log (query, old_data, ip, user_id, success) VALUES (?, ?, ?, ?, ?)',
      'types' => 'sssii'
      ],
    'log_error' => [
      'sql' => 'INSERT INTO error_log (code, error, ip, user_id, request) VALUES (?, ?, ?, ?, ?)',
      'types' => 'issis'
      ],
    'select_book_by_id' => [
      'sql' => 'SELECT * FROM book WHERE id = ?',
      'types' => 'i'
      ],
    'select_book_authors_by_book_id' => [
      'sql' => 'SELECT author_id FROM book_author WHERE book_id = ?',
      'types' => 'i'
      ],
    'select_translation_by_type_id' => [
      'sql' => 'SELECT * FROM translation WHERE type_id = ?',
      'types' => 'i'
      ],
    'select_translation_by_item_id' => [
      'sql' => 'SELECT * FROM translation WHERE type_id = ? AND item_id = ?',
      'types' => 'ii'
      ],
    'select_settings_by_id' => [
      'sql' => 'SELECT * FROM settings WHERE id = ?',
      'types' => 'i'
      ],
    'select_book_tags_by_book_id' => [
      'sql' => 'SELECT tag_id FROM book_tag WHERE book_id = ?',
      'types' => 'i'
      ],
    'select_author_by_id' => [
      'sql' => 'SELECT * FROM author WHERE id = ?',
      'types' => 'i'
      ],
    'select_book_by_id_min' => [
      'sql' => 'SELECT id, rating, rating_count, cover FROM book WHERE id = ?',
      'types' => 'i'
      ],

  );
}

?>
