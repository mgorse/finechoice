<?php

function map_to($array, $mapping)
{
  $new = array();
  foreach ($mapping as $key => $value)
    $new[$key] = $array[$mapping[$value]];
  return $new;
}

function map_from($array, $mapping)
{
  $new = array();
  $i = 0;
  foreach ($mapping as $key => $value)
    $new[$i++] = $array[$mapping[$key]];
  return $new;
}

function score($counts, $amounts, $costs, $limit) {
  $mappings = array();
  foreach ($counts as $i => $count)
    $mappings[$i] = $i;
  array_multisort($counts, $mappings);
  $amounts = map_to($amounts, $mappings);
  $costs = map_to($costs, $mappings);
  $count = count($mappings);
  $enabled = array();

  for ($i = 0; $i < $count; $i++)
    $enabled[$i] = 0;

  $remaining = $limit;
  $score = 0;
  for ($i = 0; $i < $count; $i++) {
    if ($costs[$i] < $remaining) {
      $remaining -= $costs[$i];
      $score += $amounts[$i];
      $enabled[$i] = 1;
    }
  }

  $results = map_from($enabled, $mappings);
  return $results;
}
