export function getBool(hex: ArrayBuffer, start: number = 0): boolean {
  const byte = getByte(hex, start);
  return !!byte;
}

export function getByte(hex: ArrayBuffer, start: number = 0): number {
  return new Uint8Array(hex, start)[0];
}

export function getByteString(byte: number): string {
  let bin = byte.toString(2);
  while (bin.length < 8) {
    bin = "0" + bin;
  }
  return bin;
}

export function getSByte(hex: ArrayBuffer, start: number = 0): number {
  return new Int8Array(hex, start)[0];
}

export function getSChar(hex: ArrayBuffer, start: number = 0, length: number = 0): string {
  return String.fromCharCode.apply(null, new Int8Array(hex, start, length));
}

export function getChar(hex: ArrayBuffer, start: number = 0, length: number = 0): string {
  let str = String.fromCharCode.apply(null, new Uint8Array(hex, start, length));
  const end = str.indexOf(String.fromCharCode(0));
  if (end !== -1) {
    str = str.substr(0, end);
  }
  return str.trim();
}

export function getShort(hex: ArrayBuffer, start: number = 0): number {
  return new Int16Array(hex, start, 1)[0];
}

export function getUShort(hex: ArrayBuffer, start: number = 0): number {
  return new Uint16Array(hex, start, 1)[0];
}

export function getInt(hex: ArrayBuffer, start: number = 0): number {
  hex = hex.slice(start);
  return new Int32Array(hex, 0, 1)[0];
}

export function getIntArray(hex: ArrayBuffer, start: number = 0, count: number = 1): number[] {
  hex = hex.slice(start);
  return Array.from(new Int32Array(hex, 0, count));
}

export function getUInt(hex: ArrayBuffer, start: number = 0): number {
  hex = hex.slice(start);
  return new Uint32Array(hex, 0, 1)[0];
}

export function getString(hex: ArrayBuffer, start: number = 0, length: number = 99999): string {
  let str = String.fromCharCode.apply(null, new Uint8Array(hex.slice(start)));
  const end = str.indexOf(String.fromCharCode(0));
  if (end !== -1) {
    str = str.substr(0, end);
  }
  return str.trim();
}

export function writeBool(hex: string, value: boolean, start: number = 0): void {
  // const byte = getByte(hex, start);
  // return !!byte;
}

export function writeByte(hex: string, value: number, start: number = 0): void {
  // return new Uint8Array(hex, start)[0];
}

export function writeSByte(hex: string, value: number, start: number = 0): void {
  // return new Int8Array(hex, start)[0];
}

export function writeChar(hex: string, value: string, length: number = 1, start: number = 0): void {
  // return String.fromCharCode.apply(null, new Uint8Array(hex, start, length));
}

export function writeShort(hex: string, value: number, start: number = 0): void {
  // return new Int16Array(hex, start, 1)[0];
}

export function writeInt(hex: string, value: number, start: number = 0): void {
  // return new Int32Array(hex, start, 1)[0];
}

export function writeString(hex: string, value: string, start: number = 0, length: number = 99999): void {
  hex = hex.substring(start, start + length);
  const end = hex.indexOf(String.fromCharCode(0));
  if (end !== -1) {
    hex = hex.substr(0, end);
  }
}

export function writeObject(hex: string, value: any, position: number): void {}
