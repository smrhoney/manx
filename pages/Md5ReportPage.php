<?php

require_once 'AdminPageBase.php';
require_once 'UrlInfo.php';

class MD5ReportPage extends AdminPageBase
{
	protected function getMenuType()
	{
		return MenuType::MD5Report;
	}

	protected function postPage()
	{
		PageBase::renderPage();
	}

	protected function renderBodyContent()
	{
		if (array_key_exists('operation', $this->_vars) && $this->_vars['operation'] == 'repair')
		{
			$this->renderBodyContentRepair();
		}
		else
		{
			$this->renderBodyContentReport();
		}
	}

	protected function renderBodyContentRepair()
	{
		print <<<EOH
<h1>Missing MD5 Repair Report</h1>

<p>
<table>
<tr><th>Publication</th><th>MD5</th></tr>

EOH;
		foreach (array_keys($this->_vars) as $key)
		{
			if (strpos($key, 'row') === 0)
			{
				$update = str_replace('row', 'update', $key);
				if (array_key_exists($update, $this->_vars) && ($this->_vars[$update] == 1))
				{
					list($copyId, $companyId, $pubId, $title) = explode(',', $this->_vars[$key]);
					$md5 = $this->updateMD5ForCopy($copyId);
					printf('<tr><td><a href="details.php/%d,%d">%s</a></td><td>%s</td></tr>' . "\n",
						$companyId, $pubId, $title, $md5 ? $md5 : '(unknown)');
				}
			}
		}
		print <<<EOH
</table>
</p>

<p>
<div id="form_container">
<form id="md5-report" action="md5-report.php" method="POST" name="f">
<input type="submit" value="Report" />
</form>
</div>
</p>

EOH;
	}

	private function updateMD5ForCopy($copyId)
	{
		$md5 = $this->getMD5ForCopy($copyId);
		$this->_manxDb->updateMD5ForCopy($copyId, $md5);
		return $md5;
	}

	private function getMD5ForCopy($copyId)
	{
		foreach ($this->getUrlsForCopy($copyId) as $url)
		{
			$urlInfo = new UrlInfo($url);
			$md5 = $urlInfo->md5();
			if ($md5 !== false)
			{
				return $md5;
			}
		}
		return '';
	}

	private function getUrlsForCopy($copyId)
	{
		return array_merge(array($this->_manxDb->getUrlForCopy($copyId)),
			$this->_manxDb->getMirrorsForCopy($copyId));
	}

	protected function renderBodyContentReport()
	{
		print <<<EOH
<h1>Missing MD5 Report</h1>


EOH;
		$rows = $this->_manxDb->getMissingMD5Documents();
		if (count($rows) == 0)
		{
			print "<p><strong>No documents missing MD5 signatures found.</strong></p>\n";
		}
		else
		{
			print <<<EOH
<div id="form_container">
<form id="md5-report" action="md5-report.php" method="POST" name="f">

<ol>

EOH;
			$i = 0;
			foreach ($rows as $row)
			{
				printf('<li><input type="checkbox" id="update%1$d" name="update%1$d" value="1" />' . "\n", $i);
				printf('<a href="details.php/%1$d,%2$d">%3$s</a>' . "\n",
					$row['ph_company'], $row['ph_pub'], htmlspecialchars($row['ph_title']));
				printf('<input type="hidden" id="row%1$d" name="row%1$d" value="%2$d,%3$d,%4$d,%5$s" />' . "\n",
					$i, $row['copyid'], $row['ph_company'], $row['ph_pub'], htmlspecialchars($row['ph_title']));
				print "</li>\n";
				++$i;
			}
			print <<<EOH
</ol>
<input type="hidden" name="operation" value="repair" />
<input type="submit" value="Repair" />
</form>
</div>

EOH;
		}
	}
}

?>