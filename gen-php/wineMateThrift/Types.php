<?php
namespace wineMateThrift;

/**
 * Autogenerated by Thrift Compiler (1.0.0-dev)
 *
 * DO NOT EDIT UNLESS YOU ARE SURE THAT YOU KNOW WHAT YOU ARE DOING
 *  @generated
 */
use Thrift\Base\TBase;
use Thrift\Type\TType;
use Thrift\Type\TMessageType;
use Thrift\Exception\TException;
use Thrift\Exception\TProtocolException;
use Thrift\Protocol\TProtocol;
use Thrift\Protocol\TBinaryProtocolAccelerated;
use Thrift\Exception\TApplicationException;


final class UploadTagInfoStatus {
  const UPLOAD_SUCCESS = 1;
  const UPLOAD_DUPLICATE_TAG_ID = 2;
  const UPLOAD_FAILED = 3;
  static public $__names = array(
    1 => 'UPLOAD_SUCCESS',
    2 => 'UPLOAD_DUPLICATE_TAG_ID',
    3 => 'UPLOAD_FAILED',
  );
}

class TagInfo {
  static $_TSPEC;

  /**
   * @var string
   */
  public $tagID = null;
  /**
   * @var string
   */
  public $tagPassword = null;
  /**
   * @var string
   */
  public $authenticationKey = null;
  /**
   * @var int
   */
  public $wineID = null;
  /**
   * @var int
   */
  public $rollNumber = null;
  /**
   * @var string
   */
  public $operatorID = null;

  public function __construct($vals=null) {
    if (!isset(self::$_TSPEC)) {
      self::$_TSPEC = array(
        1 => array(
          'var' => 'tagID',
          'type' => TType::STRING,
          ),
        2 => array(
          'var' => 'tagPassword',
          'type' => TType::STRING,
          ),
        3 => array(
          'var' => 'authenticationKey',
          'type' => TType::STRING,
          ),
        4 => array(
          'var' => 'wineID',
          'type' => TType::I32,
          ),
        5 => array(
          'var' => 'rollNumber',
          'type' => TType::I32,
          ),
        6 => array(
          'var' => 'operatorID',
          'type' => TType::STRING,
          ),
        );
    }
    if (is_array($vals)) {
      if (isset($vals['tagID'])) {
        $this->tagID = $vals['tagID'];
      }
      if (isset($vals['tagPassword'])) {
        $this->tagPassword = $vals['tagPassword'];
      }
      if (isset($vals['authenticationKey'])) {
        $this->authenticationKey = $vals['authenticationKey'];
      }
      if (isset($vals['wineID'])) {
        $this->wineID = $vals['wineID'];
      }
      if (isset($vals['rollNumber'])) {
        $this->rollNumber = $vals['rollNumber'];
      }
      if (isset($vals['operatorID'])) {
        $this->operatorID = $vals['operatorID'];
      }
    }
  }

  public function getName() {
    return 'TagInfo';
  }

  public function read($input)
  {
    $xfer = 0;
    $fname = null;
    $ftype = 0;
    $fid = 0;
    $xfer += $input->readStructBegin($fname);
    while (true)
    {
      $xfer += $input->readFieldBegin($fname, $ftype, $fid);
      if ($ftype == TType::STOP) {
        break;
      }
      switch ($fid)
      {
        case 1:
          if ($ftype == TType::STRING) {
            $xfer += $input->readString($this->tagID);
          } else {
            $xfer += $input->skip($ftype);
          }
          break;
        case 2:
          if ($ftype == TType::STRING) {
            $xfer += $input->readString($this->tagPassword);
          } else {
            $xfer += $input->skip($ftype);
          }
          break;
        case 3:
          if ($ftype == TType::STRING) {
            $xfer += $input->readString($this->authenticationKey);
          } else {
            $xfer += $input->skip($ftype);
          }
          break;
        case 4:
          if ($ftype == TType::I32) {
            $xfer += $input->readI32($this->wineID);
          } else {
            $xfer += $input->skip($ftype);
          }
          break;
        case 5:
          if ($ftype == TType::I32) {
            $xfer += $input->readI32($this->rollNumber);
          } else {
            $xfer += $input->skip($ftype);
          }
          break;
        case 6:
          if ($ftype == TType::STRING) {
            $xfer += $input->readString($this->operatorID);
          } else {
            $xfer += $input->skip($ftype);
          }
          break;
        default:
          $xfer += $input->skip($ftype);
          break;
      }
      $xfer += $input->readFieldEnd();
    }
    $xfer += $input->readStructEnd();
    return $xfer;
  }

  public function write($output) {
    $xfer = 0;
    $xfer += $output->writeStructBegin('TagInfo');
    if ($this->tagID !== null) {
      $xfer += $output->writeFieldBegin('tagID', TType::STRING, 1);
      $xfer += $output->writeString($this->tagID);
      $xfer += $output->writeFieldEnd();
    }
    if ($this->tagPassword !== null) {
      $xfer += $output->writeFieldBegin('tagPassword', TType::STRING, 2);
      $xfer += $output->writeString($this->tagPassword);
      $xfer += $output->writeFieldEnd();
    }
    if ($this->authenticationKey !== null) {
      $xfer += $output->writeFieldBegin('authenticationKey', TType::STRING, 3);
      $xfer += $output->writeString($this->authenticationKey);
      $xfer += $output->writeFieldEnd();
    }
    if ($this->wineID !== null) {
      $xfer += $output->writeFieldBegin('wineID', TType::I32, 4);
      $xfer += $output->writeI32($this->wineID);
      $xfer += $output->writeFieldEnd();
    }
    if ($this->rollNumber !== null) {
      $xfer += $output->writeFieldBegin('rollNumber', TType::I32, 5);
      $xfer += $output->writeI32($this->rollNumber);
      $xfer += $output->writeFieldEnd();
    }
    if ($this->operatorID !== null) {
      $xfer += $output->writeFieldBegin('operatorID', TType::STRING, 6);
      $xfer += $output->writeString($this->operatorID);
      $xfer += $output->writeFieldEnd();
    }
    $xfer += $output->writeFieldStop();
    $xfer += $output->writeStructEnd();
    return $xfer;
  }

}

class UploadTagInfoResponse {
  static $_TSPEC;

  /**
   * @var int
   */
  public $status = null;
  /**
   * @var \wineMateThrift\TagInfo
   */
  public $tagInfo = null;

  public function __construct($vals=null) {
    if (!isset(self::$_TSPEC)) {
      self::$_TSPEC = array(
        1 => array(
          'var' => 'status',
          'type' => TType::I32,
          ),
        2 => array(
          'var' => 'tagInfo',
          'type' => TType::STRUCT,
          'class' => '\wineMateThrift\TagInfo',
          ),
        );
    }
    if (is_array($vals)) {
      if (isset($vals['status'])) {
        $this->status = $vals['status'];
      }
      if (isset($vals['tagInfo'])) {
        $this->tagInfo = $vals['tagInfo'];
      }
    }
  }

  public function getName() {
    return 'UploadTagInfoResponse';
  }

  public function read($input)
  {
    $xfer = 0;
    $fname = null;
    $ftype = 0;
    $fid = 0;
    $xfer += $input->readStructBegin($fname);
    while (true)
    {
      $xfer += $input->readFieldBegin($fname, $ftype, $fid);
      if ($ftype == TType::STOP) {
        break;
      }
      switch ($fid)
      {
        case 1:
          if ($ftype == TType::I32) {
            $xfer += $input->readI32($this->status);
          } else {
            $xfer += $input->skip($ftype);
          }
          break;
        case 2:
          if ($ftype == TType::STRUCT) {
            $this->tagInfo = new \wineMateThrift\TagInfo();
            $xfer += $this->tagInfo->read($input);
          } else {
            $xfer += $input->skip($ftype);
          }
          break;
        default:
          $xfer += $input->skip($ftype);
          break;
      }
      $xfer += $input->readFieldEnd();
    }
    $xfer += $input->readStructEnd();
    return $xfer;
  }

  public function write($output) {
    $xfer = 0;
    $xfer += $output->writeStructBegin('UploadTagInfoResponse');
    if ($this->status !== null) {
      $xfer += $output->writeFieldBegin('status', TType::I32, 1);
      $xfer += $output->writeI32($this->status);
      $xfer += $output->writeFieldEnd();
    }
    if ($this->tagInfo !== null) {
      if (!is_object($this->tagInfo)) {
        throw new TProtocolException('Bad type in structure.', TProtocolException::INVALID_DATA);
      }
      $xfer += $output->writeFieldBegin('tagInfo', TType::STRUCT, 2);
      $xfer += $this->tagInfo->write($output);
      $xfer += $output->writeFieldEnd();
    }
    $xfer += $output->writeFieldStop();
    $xfer += $output->writeStructEnd();
    return $xfer;
  }

}

