<?php

namespace Pyrite\EHBL;

final class BattleType
{
  const FREE = "F";
  const TC = "TC";
  const IW = "IW";
  const DB = "DB";
  const FCHG = "FCHG";
  const CAB = "CAB";
  const ID = "ID";
  const BHG = "BHG";
  const FMC = "FMC";
  const HF = "HF";
  const UNKNOWN = "UNK";
  const TAC = "TAC";

  public static $ALL = [self::FREE, self::TC, self::IW, self::DB, self::FCHG, self::CAB, self::ID, self::BHG, self::FMC, self::HF, self::TAC];
}
