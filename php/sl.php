<?php
	header("Content-Type: text/plain; charset=utf-8");
    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);
    error_reporting(0);
    require_once '../phpquery-master/phpQuery/phpQuery.php';
    $links = array();

    if (!empty($_POST['link'])){
        $doc = phpQuery::newDocument(file_get_contents($_POST['link']));
        $links['head']['links'] = array();
        $links['head']['scripts'] = array();

        $query = $doc->find('head title');
        $links['title'] = trim(pq($query)->text());

        $query = $doc->find('head link');
        foreach ($query as $item){
            $tl = pq($item)->attr('href');
            if ($tl !== null && $tl !== "" && $tl !== "#" && $tl !== "/"){
                $links['head']['links'][pq($item)->attr('href')] = '';
            }
        }

        $query = $doc->find('head script');
        foreach ($query as $item){
            $tl = pq($item)->attr('src');
            if ($tl !== null && $tl !== "" && $tl !== "#" && $tl !== "/"){
                $links['head']['scripts'][pq($item)->attr('src')] = '';
            }
        }

        $query = $doc->find('head a');
        foreach ($query as $item){
            $tl = pq($item)->attr('href');
            if ($tl !== null && $tl !== "" && $tl !== "#" && $tl !== "/"){
                $links['head']['links'][pq($item)->attr('href')] = '';
            }
        }

        $links['body']['links'] = array();
        $links['body']['scripts'] = array();

        $query = $doc->find('body a');
        foreach ($query as $item){
            $tl = pq($item)->attr('href');
            if ($tl !== null && $tl !== "" && $tl !== "#" && $tl !== "/"){
                $links['body']['links'][pq($item)->attr('href')] = trim(pq($item)->text());
            }
        }

        $query = $doc->find('body link');
        foreach ($query as $item){
            $tl = pq($item)->attr('href');
            if ($tl !== null && $tl !== "" && $tl !== "#" && $tl !== "/"){
                $links['body']['links'][pq($item)->attr('href')] = trim(pq($item)->text());
            }
        }

        $query = $doc->find('body script');
        foreach ($query as $item){
            $tl = pq($item)->attr('src');
            if ($tl !== null && $tl !== "" && $tl !== "#" && $tl !== "/"){
                $links['body']['scripts'][pq($item)->attr('src')] = '';
            }
        }

        $query = $doc->find('body:not("type=text")');
        $links['content'] = trim(htmlspecialchars(pq($query)->text()));
        phpQuery::unloadDocuments();

        $path = '';

        function editUrl($key){
            global $path;
            $mas = parse_url($_POST['link']);
            $path = $mas["scheme"] . "://" . $mas['host'] . '/' . $key;
            if (mb_substr($key,0,4) == 'http'){
                $path = $key;
            }
            if(mb_substr($key,0,1) == '/' && mb_substr($key,0,2) != '//'){
                $path = $mas["scheme"] . "://" . $mas['host'] . $key;
            }
            if (mb_substr($key,0,3) == '../'){
                $path = $mas["scheme"] . "://" . $mas['host'] . '/' . mb_substr($key,3);
            }
            if (mb_substr($key,0,2) == '//'){
                $path = $mas["scheme"] . "://" . mb_substr($key,2);
            }
        }

    }

?>

<?php if (!empty($links)): ?>
<ul>
    <li><a href="#">Document</a>
        <ul>
            <li><a href="#">Head</a>
                <ul>
                    <li><a href="#">Title</a>
                        <ul>
                            <li><p class="link-title"><?= $links['title']; ?></p></li>
                        </ul>
                    </li>
                    <li><a href="#">Links</a>
                        <ul>
                            <?php foreach ($links['head']['links'] as $key => $value): ?>
                                <?php editUrl($key); ?>
                                <li><p><a href="<?= $path; ?>" target="_blank"><?php if (empty($value)) {echo $path;} else {echo $value;} ?></a></p></li>
                            <?php endforeach; ?>
                        </ul>
                    </li>
                    <li><a href="#">Scripts</a>
                        <ul>
                            <?php foreach ($links['head']['scripts'] as $key => $value): ?>
                                <?php editUrl($key); ?>
                                <li><p><a href="<?= $path; ?>" target="_blank"><?= $path; ?></a></p></li>
                            <?php endforeach; ?>
                        </ul>
                    </li>
                </ul>
            </li>
            <li><a href="#">Body</a>
                <ul>
                    <li><a href="#">Links</a>
                        <ul>
                            <?php foreach ($links['body']['links'] as $key => $value): ?>
                                <?php editUrl($key); ?>
                                <li><p><a href="<?= $path; ?>" target="_blank"><?php if (empty($value)) {echo $path;} else {echo $value;} ?></a></p></li>
                            <?php endforeach; ?>
                        </ul>
                    </li>
                    <li><a href="#">Scripts</a>
                        <ul>
                            <?php foreach ($links['body']['scripts'] as $key => $value): ?>
                                <?php editUrl($key); ?>
                                <li><p><a href="<?= $path; ?>" target="_blank"><?= $path; ?></a></p></li>
                            <?php endforeach; ?>
                        </ul>
                    </li>
                    <li><a href="#">Content</a>
                        <ul>
                            <li><p class="content"><?= $links['content']; ?></p></li>
                        </ul>
                    </li>
                </ul>
            </li>
        </ul>
    </li>
</ul>
<?php endif; ?>
