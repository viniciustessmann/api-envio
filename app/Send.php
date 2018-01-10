<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Send extends Model
{
    protected $table = 'sends';

    public function setMin($min) {
        $this->attributes['min'] = $min;
    }

    public function setMax($max) {
        $this->attributes['max'] = $max;
    }

    public function setType($type) {
        $this->attributes['type'] = $type;
    }

    public function setL1($l1) {
        $this->attributes['l1'] = $l1;
    }

    public function setL2($l2) {
        $this->attributes['l2'] = $l2;
    }

    public function setL3($l3) {
        $this->attributes['l3'] = $l3;
    }

    public function setL4($l4) {
        $this->attributes['l4'] = $l4;
    }

    public function setE1($e1) {
        $this->attributes['e1'] = $e1;
    } 

    public function setE2($e2) {
        $this->attributes['e2'] = $e2;
    }

    public function setE3($e3) {
        $this->attributes['e3'] = $e3;
    }

    public function setE4($e4) {
        $this->attributes['e4'] = $e4;
    }

    public function setN1($n1) {
        $this->attributes['n1'] = $n1;
    } 

    public function setN2($n2) {
        $this->attributes['n2'] = $n2;
    }

    public function setN3($n3) {
        $this->attributes['n3'] = $n3;
    }

    public function setN4($n4) {
        $this->attributes['n4'] = $n4;
    }

    public function setN5($n5) {
        $this->attributes['n5'] = $n5;
    }

    public function setN6($n6) {
        $this->attributes['n6'] = $n6;
    }

    public function setI1($i1) {
        $this->attributes['i1'] = $i1;
    } 

    public function setI2($i2) {
        $this->attributes['i2'] = $i2;
    }

    public function setI3($i3) {
        $this->attributes['i3'] = $i3;
    }

    public function setI4($i4) {
        $this->attributes['i4'] = $i4;
    }

    public function setI5($i5) {
        $this->attributes['i5'] = $i5;
    }

    public function setI6($i6) {
        $this->attributes['i6'] = $i6;
    }

    public function getMax() {
        return $this->attributes['max'];
    }

    public function getMin() {
        return $this->attributes['min'];
    }

    public function getType() {
        return $this->attributes['type'];
    }

    public function getL1() {
        return $this->attributes['l1'];
    }

    public function getL2() {
        return $this->attributes['l2'];
    }

    public function getL3() {
        return $this->attributes['l3'];
    }

    public function getL4() {
        return $this->attributes['l4'];
    }

    public function getE1() {
        return $this->attributes['e1'];
    } 

    public function getE2() {
        return $this->attributes['e2'];
    }

    public function getE3() {
        return $this->attributes['e3'];
    }

    public function getE4() {
        return $this->attributes['e4'];
    }

    public function getN1() {
        return $this->attributes['n1'];
    } 

    public function getN2() {
        return $this->attributes['n2'];
    }

    public function getN3() {
        return $this->attributes['n3'];
    }

    public function getN4() {
        return $this->attributes['n4'];
    }

    public function getN5() {
        return $this->attributes['n5'];
    }

    public function getN6() {
        return $this->attributes['n6'];
    }

    public function getI1() {
        return $this->attributes['i1'];
    } 

    public function getI2() {
        return $this->attributes['i2'];
    }

    public function getI3() {
        return $this->attributes['i3'];
    }

    public function getI4() {
        return $this->attributes['i4'];
    }

    public function getI5() {
        return $this->attributes['i5'];
    }

    public function getI6() {
        return $this->attributes['i6'];
    }

    public function findAll() {
        return $this::all()->toArray();
    }
}
