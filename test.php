<?php
$data = [];
$data = [
  "model1" => [
    "id" => 2,
    "name" => "sato"
  ],
  "model2" => [
    "model1_id" => 2,
    "name" => "sssss"
  ]
];

if (isset($data['model1'])) {
  echo "find model1.\n";
}

if (isset($data['model1']) && isset($data['model1']['id'])) {
  echo "model1 id" . $data['model1']['id'] . "\n";
}

