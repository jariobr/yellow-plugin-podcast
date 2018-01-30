<?php
// Podcast Plugin
// Based on the yellow Feed plugin, https://github.com/datenstrom/yellow-plugins/tree/master/feed
// Copyright (c) 2013-2017 Datenstrom, https://datenstrom.se
// This file may be used and distributed under the terms of the public license.

class YellowPodcast
{
	const VERSION = "0.7.1";
	var $yellow;			//access to API
	
	// Handle initialisation
	function onLoad($yellow)
	{
		$this->yellow = $yellow;
		$this->yellow->config->setDefault("podcastLocation", "/podcast/");
		$this->yellow->config->setDefault("podcastFileXml", "podcast.xml");
		$this->yellow->config->setDefault("podcastFilter", "");
		$this->yellow->config->setDefault("podcastPaginationLimit", "30");
		$this->yellow->config->setDefault("podcastMimeType", "audio/mpeg");
	}

	// Handle page parsing
	function onParsePage()
	{
		if($this->yellow->page->get("template")=="podcast")
		{
			$podcastFilter = $this->yellow->config->get("podcastFilter");
			$chronologicalOrder = ($this->yellow->config->get("podcastFilter")!="blog");
			$pagination = $this->yellow->config->get("contentPagination");
			if($_REQUEST[$pagination]==$this->yellow->config->get("podcastFileXml"))
			{
				$pages = $this->yellow->pages->index(false, false);
				if(!empty($podcastFilter)) $pages->filter("template", $podcastFilter);
				$pages->sort($chronologicalOrder ? "modified" : "published", false);
				$pages->limit($this->yellow->config->get("podcastPaginationLimit"));
				$this->yellow->page->setLastModified($pages->getModified());
				$this->yellow->page->setHeader("Content-Type", "application/rss+xml; charset=utf-8");
				$output = "<?xml version=\"1.0\" encoding=\"utf-8\"\077>\r\n";
				$output .= "<rss version=\"2.0\" xmlns:content=\"http://purl.org/rss/1.0/modules/content/\" xmlns:dc=\"http://purl.org/dc/elements/1.1/\">\r\n";
				$output .= "<channel>\r\n";
				$output .= "<title>".$this->yellow->page->getHtml("sitename")." - ".$this->yellow->text->getHtml("PodcastFeed")."</title>\r\n";
				$output .= "<link>".$this->yellow->page->scheme."://".$this->yellow->page->address.$this->yellow->page->base."/"."</link>\r\n";
				$output .= "<description>".$this->yellow->page->getHtml("tagline")."</description>\r\n";
				$output .= "<language>".$this->yellow->page->getHtml("language")."</language>\r\n";
				foreach($pages as $page)
				{
					$timestamp = strtotime($page->get($chronologicalOrder ? "modified" : "published"));
					$content = $this->yellow->toolbox->createTextDescription($page->getContent(), 0, false, "<!--more-->", " <a href=\"".$page->getUrl()."\">".$this->yellow->text->getHtml("blogMore")."</a>");
					$output .= "<item>\r\n";
					$output .= "<title>".$page->getHtml("title")."</title>\r\n";
					$output .= "<link>".$page->getUrl()."</link>\r\n";
					$output .= "<pubDate>".date(DATE_RSS, $timestamp)."</pubDate>\r\n";
					$output .= "<guid isPermaLink=\"false\">".$page->getUrl()."?".$timestamp."</guid>\r\n";
					$output .= "<dc:creator>".$page->getHtml("author")."</dc:creator>\r\n";
					$output .= "<description>".$page->getHtml("description")."</description>\r\n";
					$output .= "<content:encoded><![CDATA[".$content."]]></content:encoded>\r\n";
					if($page->isExisting("mediafile")) $output .= "<enclosure url=\"".$page->getHtml("mediafile")."\" length=\"0\" type=\"".$this->yellow->config->get("podcastMimeType")."\" />\r\n";
					$output .= "</item>\r\n";
				}
				$output .= "</channel>\r\n";
				$output .= "</rss>\r\n";
				$this->yellow->page->setOutput($output);
			} else {
				$pages = $this->yellow->pages->index(false, false);
				if(!empty($podcastFilter)) $pages->filter("template", $podcastFilter);
				$pages->sort($chronologicalOrder ? "modified" : "published");
				$pages->pagination($this->yellow->config->get("podcastPaginationLimit"));
				if(!$pages->getPaginationNumber()) $this->yellow->page->error(404);
				$this->yellow->page->set("podcastChronologicalOrder", $chronologicalOrder);
				$this->yellow->page->setPages($pages);
				$this->yellow->page->setLastModified($pages->getModified());
			}
		}
	}
	
	// Handle page extra HTML data
	function onExtra($name)
	{
		$output = NULL;
		if($name=="header")
		{
			$pagination = $this->yellow->config->get("contentPagination");			
			$locationPodcast = $this->yellow->config->get("serverBase").$this->yellow->config->get("podcastLocation");
			$locationPodcast .= $this->yellow->toolbox->normaliseArgs("$pagination:".$this->yellow->config->get("podcastFileXml"), false);
			$output = "<link rel=\"alternate\" type=\"application/rss+xml\" href=\"$locationPodcast\" />\n";
		}
		return $output;
	}
}

$yellow->plugins->register("podcast", "YellowPodcast", YellowPodcast::VERSION);
?>
