<?php
// generate id code
function generateId($str) {
    return base64_encode($str);
}