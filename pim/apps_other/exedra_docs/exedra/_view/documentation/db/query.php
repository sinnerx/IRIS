<div class='text'>
This section will cover database querying part using Query Builder, like select, where, or_where, order_by, limit, join and raw query.
</div>
<div class='text'><div class='text-title'>1. db::get() - Execution </div>
This method execute the prepared sql. 
<pre><code class='hljs php'>
$query	= db::select('col,col2')->from('mytable')->where('col',10)->limit(10)->get();
// execute SELECT * FROM mytable
</code></pre> 

It can also be use to retrieve all records :
<pre><code class='hljs php'>
$mytable	= db::get('mytable');
// execute SELECT * FROM mytable
</code></pre> 
</div>
<div class='text'><div class='text-title'>2. db::select() - Column Selects</div>
Let you write the SELECT part for the query preparation.
<pre><code class='hljs php'>
// unchained.
db::select("mycolumn,mycolumn2");
$query	= db::get('mytable');

// or chaining.
$query	= db::select("mycolumn,mycolumn2")->get("mytable");

// Both execute SELECT mycolumn,mycolumn2 FROM mytable
</code></pre> 
</div>
<div class='text'><div class='text-title'>3. db::from() - Tables</div>
Let you write the FROM portion for query preparation
<pre><code class='hljs php'>
db::select("mycolumn,mycolumn2");
db::from("mytable");
$query	= db::get();
// execute SELECT mycolumn,mycolumn2 FROM mytable
</code></pre> 
</div>
<div class='text'><div class='text-title'>4. db::join() - Joining Tables </div>
Let you write JOIN part for query preparation
<pre><code class='hljs php'>
db::select("mycolumn,mycolumn2");
db::from("news");
db::where('newsID',10);
db::join("news_comment","news.newsID = news_comment.newsID","left");
$query = db::get();
// execute : 
// SELECT * FROM mytable WHERE newsID = 10
// LEFT JOIN news_comment ON news.newsID = news_comment.newsID 
</code></pre> 
</div>
<div class='text'><div class='text-title'>5. db::where() - Where Conditions</div>
Let you write WHERE condition in preparing an sql. There're few different way in using this method :<br>
<div class='text-subtitle'>1. Simple where</div>
<pre><code class='hljs php'>
db::where("newsID",10);
// WHERE newsID = '10'
</code></pre>
That write a basic equation for the sql statement.<br><br>

<div class='text-subtitle'>2. Multiple where</div>
<pre><code class='hljs php'>
db::where("newsID",10);
db::where("newsSlug","exedra-newborn-framework");
// WHERE (newsID = '10') AND (newsSlug = 'exedra-newborn-framework')
</code></pre><br>
<div class='text-subtitle'>3. Custom Operator</div>
<pre><code class='hljs php'>
db::where("newsID >",10);
db::where("newsSlug !=","exedra-newborn-framework");
// WHERE (newsID > '10') AND (newsSlug != 'exedra-newborn-framework')
</code></pre><br>
<div class='text-subtitle'>4. By Associative Array</div>
<pre><code class='hljs php'>
$where	= Array(
		"newsID"=>10,
		"newsSlug"=>"exedra-thenew-framework",
		"newsDate >"=>"2014-17-3",
		"newsStatus !"="3"
				);
// WHERE (newsID = '10' AND newsSlug = 'exedra-thenew-framework' AND newsDate > '2014-17-3' AND newsStatus != '3')
</code></pre><br>

<div class='text-subtitle'>5. String based WHERE</div>
<pre><code>
db::where("WHERE newsID = '10' AND newsSlug = 'exedra-newborn-framework'");
</code></pre><br>

<div class='text-subtitle'>6. Prepared where</div>
<pre><code>
db::where("newsID = '?' AND newsSlug = '?'",Array(10,"exedra-framework"));
// WHERE newsID = '10' AND newsSlug = 'exedra-framework'
</code></pre>
</div>
<div class='text'><div class='text-title'>2. db::order_by() - Order By</div>
Let you write an Order By portion for the sql statement.
<div class='text-subtitle'>1. Simple order by</div>
<pre><code>
db::order_by("newsID","desc");
</code></pre>
<div class='text-subtitle'>2. Order by with array</div>
<pre><code>
$order	= Array(
		"newsID"=>"desc",
		"newsTitle"=>"asc"
				);
db::order_by($order);
</code></pre>
</div>