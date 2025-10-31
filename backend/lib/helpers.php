<?php
// backend/helpers.php

function jsonResponse($data, $status = 200)
{
  http_response_code($status);
  header('Content-Type: application/json');
  echo json_encode($data);
  exit;
}

function getJsonInput()
{
  $body = file_get_contents('php://input');
  $data = json_decode($body, true);
  return is_array($data) ? $data : [];
}

function respondError($message, $status = 400)
{
  jsonResponse(['success' => false, 'error' => $message], $status);
}
