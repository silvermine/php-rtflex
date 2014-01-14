<?php

namespace RTFLex\tree;
use RTFLex\tokenizer\RTFToken;


class RTFGroup {
    private $controls = array();
    private $content = array();
    private $parent;


    public function extractText($allowInvisible = false) {
        if (!$this->isPrintableText() && !$allowInvisible) {
            return "";
        }

        $text = "";
        foreach ($this->content as $piece) {
            $t = $piece->extractText();
            if ($t == ';') {
                var_dump($piece);
            }
            $text .= $t;
        }

        return $text;
    }


    public function hasControlWord($name) {
        foreach ($this->controls as $control) {
            if ($control->getName() == $name) {
                return true;
            }
        }
        return false;
    }


    public function isPrintableText() {
        // These control words define a text group that has a non-printable destination
        $nonPrintableWords = array(
            'aftncn','aftnsep','aftnsepc','annotation','atnauthor','atndate','atnicn','atnid',
            'atnparent','atnref','atntime','atrfend','atrfstart','author','background',
            'bkmkend','bkmkstart','blipuid','buptim','category','colorschememapping',
            'colortbl','comment','company','creatim','datafield','datastore','defchp','defpap',
            'do','doccomm','docvar','dptxbxtext','ebcend','ebcstart','factoidname','falt',
            'fchars','ffdeftext','ffentrymcr','ffexitmcr','ffformat','ffhelptext','ffl',
            'ffname','ffstattext','field','file','filetbl','fldinst','fldrslt','fldtype',
            'fname','fontemb','fontfile','fonttbl','footer','footerf','footerl','footerr',
            'footnote','formfield','ftncn','ftnsep','ftnsepc','g','generator','gridtbl',
            'header','headerf','headerl','headerr','hl','hlfr','hlinkbase','hlloc','hlsrc',
            'hsv','htmltag','info','keycode','keywords','latentstyles','lchars','levelnumbers',
            'leveltext','lfolevel','linkval','list','listlevel','listname','listoverride',
            'listoverridetable','listpicture','liststylename','listtable','listtext',
            'lsdlockedexcept','macc','maccPr','mailmerge','maln','malnScr','manager','margPr',
            'mbar','mbarPr','mbaseJc','mbegChr','mborderBox','mborderBoxPr','mbox','mboxPr',
            'mchr','mcount','mctrlPr','md','mdeg','mdegHide','mden','mdiff','mdPr','me',
            'mendChr','meqArr','meqArrPr','mf','mfName','mfPr','mfunc','mfuncPr','mgroupChr',
            'mgroupChrPr','mgrow','mhideBot','mhideLeft','mhideRight','mhideTop','mhtmltag',
            'mlim','mlimloc','mlimlow','mlimlowPr','mlimupp','mlimuppPr','mm','mmaddfieldname',
            'mmath','mmathPict','mmathPr','mmaxdist','mmc','mmcJc','mmconnectstr',
            'mmconnectstrdata','mmcPr','mmcs','mmdatasource','mmheadersource','mmmailsubject',
            'mmodso','mmodsofilter','mmodsofldmpdata','mmodsomappedname','mmodsoname',
            'mmodsorecipdata','mmodsosort','mmodsosrc','mmodsotable','mmodsoudl',
            'mmodsoudldata','mmodsouniquetag','mmPr','mmquery','mmr','mnary','mnaryPr',
            'mnoBreak','mnum','mobjDist','moMath','moMathPara','moMathParaPr','mopEmu',
            'mphant','mphantPr','mplcHide','mpos','mr','mrad','mradPr','mrPr','msepChr',
            'mshow','mshp','msPre','msPrePr','msSub','msSubPr','msSubSup','msSubSupPr','msSup',
            'msSupPr','mstrikeBLTR','mstrikeH','mstrikeTLBR','mstrikeV','msub','msubHide',
            'msup','msupHide','mtransp','mtype','mvertJc','mvfmf','mvfml','mvtof','mvtol',
            'mzeroAsc','mzeroDesc','mzeroWid','nesttableprops','nextfile','nonesttables',
            'objalias','objclass','objdata','object','objname','objsect','objtime','oldcprops',
            'oldpprops','oldsprops','oldtprops','oleclsid','operator','panose','password',
            'passwordhash','pgp','pgptbl','picprop','pict','pn','pnseclvl','pntext','pntxta',
            'pntxtb','printim','private','propname','protend','protstart','protusertbl','pxe',
            'result','revtbl','revtim','rsidtbl','rxe','shp','shpgrp','shpinst',
            'shppict','shprslt','shptxt','sn','sp','staticval','stylesheet','subject','sv',
            'svb','tc','template','themedata','title','txe','ud','upr','userprops',
            'wgrffmtfilter','windowcaption','writereservation','writereservhash','xe','xform',
            'xmlattrname','xmlattrvalue','xmlclose','xmlname','xmlnstbl',
            'xmlopen');

        $controlWords = array_map(function($obj) {
            return $obj->getName();
        }, $this->controls);

        $dest = array_intersect($nonPrintableWords, $controlWords);
        return count($dest) == 0;
    }


    public function listChildren() {
        $children = array();

        foreach ($this->content as $piece) {
            if ($piece instanceof RTFGroup) {
                $children[] = $piece;
            }
        }

        return $children;
    }


    public function pushContent(RTFToken $token) {
        $type = $token->getType();
        if ($type != RTFToken::T_CONTROL_SYMBOL && $type != RTFToken::T_TEXT) {
            throw new Exception("Content must be either T_CONTROL_SYMBOL or T_TEXT");
        }

        array_push($this->content, $token);
    }


    public function pushControlWord(RTFToken $token) {
        if ($token->getType() != RTFToken::T_CONTROL_WORD) {
            throw new Exception("Incorrect token type");
        }

        array_push($this->controls, $token);
    }


    public function pushGroup(RTFGroup $group) {
        $group->setParent($this);
        array_push($this->content, $group);
    }


    protected function setParent(RTFGroup $parent) {
        $this->parent = $parent;
    }
}
