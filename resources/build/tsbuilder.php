<?php
namespace Pyrite\Build;

class TSBuilder extends Builder
{
    protected $getSelf = 'this';
    protected $debugFN = 'toJSON';

    protected $singleArrayTemplate = <<<SGL

		this.NAME = [];
		offset = START_OFF;
		for (let i = 0; i < COUNT_INNER; i++) {
			const t = INNERPROP
			this.NAME.push(t);
			offset += UPDATE_OFF;
		}
SGL;

    protected $singleArrayOutTemplate = <<<SGL

		offset = START_OFF;
		for (let i = 0; i < COUNT_INNER; i++) {
			const t = INNERPROP;
			WRITE_OUT
			offset += UPDATE_OFF;
		}
SGL;

    protected $constructorHead = <<<TXT

	public constructor(hex: ArrayBuffer, tie?: IMission) {
		super(hex, tie);
		this.beforeConstruct();
		let offset = 0;
TXT;

    protected $constructorEnd = <<<TXT
		this.afterConstruct();
	}

TXT;

    public function __construct($dir, $out = null)
    {
        $this->dir = $dir;
        $this->out = $out ? $out : $dir;

        $this->classStart = file_get_contents("$dir/build/classStartTS.txt");
        $this->classEnd = file_get_contents("$dir/build/classEndTS.txt");
        $this->constStart = file_get_contents("$dir/build/constStartTS.txt");
        $this->constEnd = file_get_contents("$dir/build/constEndTS.txt");
    }

    protected function getMethodHeader($name, $visibility = 'protected', $returnType = '')
    {
        $ret = $returnType ? ": $returnType" : '';
        return "\t{$visibility} {$name}(){$ret} {\n";
    }

    protected function callMethod($methodName, $object = '', $args = [])
    {
        if ($object) {
            $object .= '.';
        }
        $a = implode(", ", $args);
        return "{$object}{$methodName}({$a})";
    }

    protected function callConstructor($typeName, $h, $offset)
    {
        return "new $typeName({$h}.slice($offset), this.TIE)\n";
    }

    protected function initVar($name, $value = 0, $constant = false)
    {
        $v = $this->getVariableExp($name);
        $lv = $constant ? 'const' : 'let';
        return "{$lv} {$v} = {$value};";
    }

    protected function getVariableExp($name)
    {
        return "{$name}";
    }

    protected function initConst($name, $value)
    {
        return "\tpublic static {$name} = {$value};";
    }

    protected function getPropertyDeclaration($prop)
    {
        $type = $this->typescriptType($prop['type']); // list type, path ?
        $this->importedTypes[] = $type;
        if (isset($prop['count'])) {
            $type .= "[]";
        }
        return "\tpublic {$prop['name']}: {$type};{$prop['comment']}";
    }

    protected function typescriptType($type)
    {
        switch ($type) {
            case 'SHORT':
            case 'INT':
            case 'BYTE':
            case 'SBYTE':
                return 'number';
            case 'BOOL':
                return 'boolean';
        }
        if (substr($type, 0, 3) == 'STR') {
            return 'string';
        } elseif (substr($type, 0, 4) == 'CHAR') {
            return 'string';
        } else {
            return $type;
        }
    }

    protected function getAbstractFunction($funcName)
    {
        return "protected abstract $funcName;\n";
    }

    protected function arrayOutput($array, $keyName, $valueName)
    {
        $out = "{\n";
        $debug = array_map(function ($p) use ($keyName, $valueName) {
            return "\t\t\t{$p[$keyName]}: {$p[$valueName]}";
        }, $array);
        $out .= implode(",\n", $debug);
        $out .= "\n\t\t};";
        return $out;
    }

    protected function getEnumLabelExp($name)
    {
        return $this->getPropertyExp("{$name}Label");
    }

    protected function enumLookup(array $prop)
    {
        $fn = "{$prop['name']}Label";
        $en = strtoupper($prop['enum']);
        $tp = $this->getPropertyExp($prop['name']);
        $this->importedTypes[] = 'Constants';
        return <<<ELU
	public get {$fn}() {
		return Constants.{$en}[$tp] || "Unknown";
	}

ELU;
    }

    protected function getPropertyExp($property, $object = '')
    {
        if (!$object) {
            $object = 'this';
        }
        return "{$object}.{$property}";
    }

    protected function getConst($name)
    {
        return "{$this->currentClass}Base.{$name}";
    }

    protected function writeClass(array $lines, $className)
    {
        if (count($this->importedHex)) {
            array_unshift($lines, "");
            $imports = array_unique($this->importedHex);
            sort($imports);
            $glue = count($imports) > 5 ? ",\n" : ", ";
            $used = implode($glue, $imports);
            array_unshift($lines, "import { {$used} } from \"../../hex\";");
        }

        $imports = array_filter(array_unique($this->importedTypes), function ($type) {
            return !in_array($type, ['number', 'boolean', 'string']);
        });
        if (count($imports)) {
            array_unshift($lines, "");
            natcasesort($imports);
            $imports = array_reverse($imports);
            //surprisingly complicated to reverse text sort these so they are in tslint order

            foreach ($imports as $import) {
                $kImport = $this->camelToKebab($import);
                array_unshift($lines, "import { {$import} } from \"../{$kImport}\";");
            }
        }

        $fileContents = implode("\n", $lines);
        $fclass = $this->camelToKebab($className);
        $fbase = $fclass . '-base';
        file_put_contents("{$this->out}{$this->platform}/gen/{$fbase}.ts", $this->filterFileContents($fileContents));
        echo htmlentities($fileContents);

        $base = $className . 'Base';

        $impl = "import { {$base} } from \"./gen/$fbase\";\n\nexport class {$className} extends {$base} {\n}\n";

        $implPath = "{$this->out}{$this->platform}/{$fclass}.ts";
        if (!file_exists($implPath)) {
            file_put_contents($this->filterFileContents($implPath), $impl);
        }
        $this->importedTypes = [];
        $this->importedHex = [];
        $this->currentClass = '';
    }

    protected function filterFileContents($contents)
    {
        return str_replace("\t", '  ', $contents);
    }

    protected function writeConstants()
    {
        $fileContents = str_replace('PLATFORM', $this->platform, $this->constStart);
        $enumsTS = '';
        foreach ($this->enums as $name => $items) {
            $upName = strtoupper($name);
            $fileContents .= "\tpublic static $upName = {\n";
            $enumsTS .= "export enum $name {\n";
            $eUsed = [];
            foreach ($items as $value => $label) {
                if (substr($value, 0, 3) === 'Var') {
                    $fileContents .= "\t\t// $value $label\n";
                } else {
                    $value = hexdec($value);
                    $fileContents .= "\t\t$value: \"$label\",\n";
                    $eLabel = str_replace('%', 'Percent', $label);
                    $eLabel = str_replace('&', 'n', $eLabel);
                    $eLabel = str_replace([' ', '(', ')', '-', '/', ',', '%', '\'', '?'], '', $eLabel);
                    if (is_numeric($eLabel[0])) {
                        $eLabel = 'n' . $eLabel;
                    }

                    // protect against duplicate enums \/ after making sure they are legal characters ^
                    if (isset($eUsed[$eLabel])) {
                        $eLabel = '// ' . $label;
                    } else {
                        $eUsed[$eLabel] = 1;
                    }
                    $enumsTS .= "\t$eLabel = $value,\n";
                }
            }
            $fileContents .= "\t};\n\n";
            $enumsTS .= "}\n\n";

        }
        $fileContents .= $this->constEnd;

        $fileContents .= "\n\n" . $enumsTS;

        file_put_contents("{$this->out}{$this->platform}/constants.ts", $this->filterFileContents($fileContents));
        file_put_contents(
            "{$this->out}{$this->platform}/build/const.json",
            json_encode($this->enums, JSON_PRETTY_PRINT)
        );
        echo json_encode($this->enums, JSON_PRETTY_PRINT);
    }
}
