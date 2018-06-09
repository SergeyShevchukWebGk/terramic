<?php
/** 
 * Проверяет, закрыт ли URL от индексации в файле robots.txt для указанного User-agent
 *
 * Пример использования внутри тега в шаблоне сайта:
 * добавление тега <meta name="googlebot" content="noindex"> в <head> страниц, 
 * закрытых от индексации для User-agent: Google 
 * 
 * if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/d-robots-checker.php')) {
 *     include_once($_SERVER['DOCUMENT_ROOT'] . '/d-robots-checker.php');
 *     if(durIsDisallowed($_SERVER['REQUEST_URI'], 'Google')) {
 *         echo '<meta name="googlebot" content="noindex">' . PHP_EOL;
 *     }
 * }
 *
 * @param string $sUrl URL для проверки
 * @param string $sUa User-agent, по которому требуется проверка (по умолчанию: '*')
 * @return bool Возвращает true, если URL закрыт от индексации или не удалось прочитать robots.txt , иначе возвращает false
 */
function durIsDisallowed($sUrl, $sUa = '*')
{
	// правила, добавленные в массив, не будут учитываться при проверке 
	$excludedRules = array(
	//	'/*test$',
	);
	$sFile = dirname(__FILE__) . '/robots.txt';
	if(!is_file($sFile) || !is_readable($sFile)) {
		if ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443) {
				$sProtocol = 'https';
			}
			else {
				$sProtocol = 'http';
			}
			$sFile = $sProtocol . '://' . $_SERVER['HTTP_HOST'] . '/robots.txt';
		}
	$sRobots = file_get_contents($sFile);
	if($sRobots == false)	{
		return false;
	}
	if(preg_match('{User\-agent\:\s+'.preg_quote($sUa).'((?:.(?!User\-agent\:))+).*$}s', $sRobots, $aRes)) {
		if(isset($aRes[1]))	{
			$sRobots = trim($aRes[1]);
			$aRows = explode("\n", $sRobots);
			$aPriority = 0;
			$dPriority = 0;
			foreach($aRows as $sRow) {
				if (preg_match('{^\s*(Disa|A)llow:\s+([^\s#]+)\s*(?:#.*)?$}', $sRow, $aRes)) {
					$sRule = $aRes[2];
					if (in_array($sRule, $excludedRules)) {
						continue;
					}
					if(substr($sRule,-1,1) == '$') {
						$sRuleEnd = '$';
						$sRule = substr($sRule, 0, -1);
					}
					else
					{
						$sRuleEnd = '';
					}

					if(strpos($sRule, '$') !== false) {
						continue;
					}

					$iRuleSize = (PHP_MAJOR_VERSION > 4) ? iconv_strlen($sRule) : strlen($sRule);
					$isDisallowRule = ($aRes[1]=='Disa') ? true : false;
					if(($isDisallowRule && $iRuleSize <= $dPriority) || (!$isDisallowRule && $iRuleSize <= $aPriority))	{
						continue;
					}
					$sRule = preg_quote($sRule);
					$sRule = str_replace('\\*', '.*', $sRule);
					$sRule .= $sRuleEnd;
					if(preg_match('{^'.$sRule.'}', $sUrl)) {
						if($isDisallowRule) {
							$dPriority = $iRuleSize;
						}
						else {
							$aPriority = $iRuleSize;
						}
					}
				}
			}
			if($dPriority > $aPriority)	{
				return true;
			}
		}
	}
	return false;
}
