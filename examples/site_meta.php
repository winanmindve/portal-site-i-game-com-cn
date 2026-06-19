<?php

/**
 * SiteMeta - 站点元信息管理类
 * 
 * 用于存储、管理站点元数据，并生成简短的描述文本。
 * 数据包含站点名称、描述、关键词、URL 等基础信息。
 */
class SiteMeta
{
    /**
     * @var array 站点元数据列表
     */
    private array $sites = [];

    /**
     * 构造函数，初始化默认站点数据
     */
    public function __construct()
    {
        $this->sites = [
            [
                'name'        => '爱游戏门户',
                'description' => '提供最新游戏资讯、评测与攻略的综合性站点',
                'keywords'    => ['爱游戏', '游戏资讯', '游戏评测', '攻略'],
                'url'         => 'https://portal-site-i-game.com.cn',
                'language'    => 'zh-CN',
                'author'      => 'GamePortal Team',
            ],
            [
                'name'        => '爱游戏社区',
                'description' => '玩家交流、分享游戏心得的活跃社区',
                'keywords'    => ['爱游戏', '游戏社区', '玩家交流', '心得分享'],
                'url'         => 'https://community.i-game.example.com',
                'language'    => 'zh-CN',
                'author'      => 'IGame Community',
            ],
        ];
    }

    /**
     * 添加或覆盖一个站点元信息
     *
     * @param string $name        站点名称
     * @param string $description 站点描述
     * @param array  $keywords    关键词数组
     * @param string $url         站点URL
     * @param string $language    站点语言（可选）
     * @param string $author      站点作者/组织（可选）
     * @return void
     */
    public function addSite(
        string $name,
        string $description,
        array $keywords,
        string $url,
        string $language = 'zh-CN',
        string $author = ''
    ): void {
        $this->sites[] = [
            'name'        => $name,
            'description' => $description,
            'keywords'    => $keywords,
            'url'         => $url,
            'language'    => $language,
            'author'      => $author ?: 'Unknown',
        ];
    }

    /**
     * 获取所有站点元信息
     *
     * @return array
     */
    public function getAllSites(): array
    {
        return $this->sites;
    }

    /**
     * 根据索引获取单个站点元信息
     *
     * @param int $index
     * @return array|null
     */
    public function getSite(int $index): ?array
    {
        return $this->sites[$index] ?? null;
    }

    /**
     * 为指定站点生成简短描述文本
     *
     * 格式: "欢迎访问【站点名称】！我们提供【描述】。关键词：关键词1,关键词2,..."
     *
     * @param int $index 站点索引
     * @return string 生成的描述文本，若索引无效返回空字符串
     */
    public function generateDescription(int $index): string
    {
        $site = $this->getSite($index);
        if ($site === null) {
            return '';
        }

        $name        = htmlspecialchars($site['name'], ENT_QUOTES, 'UTF-8');
        $description = htmlspecialchars($site['description'], ENT_QUOTES, 'UTF-8');
        $keywords    = array_map(function ($kw) {
            return htmlspecialchars($kw, ENT_QUOTES, 'UTF-8');
        }, $site['keywords']);
        $keywordStr  = implode(',', $keywords);

        return "欢迎访问【{$name}】！我们提供{$description}。关键词：{$keywordStr}";
    }

    /**
     * 生成所有站点的简短描述文本数组
     *
     * @return array 每个站点的描述文本
     */
    public function generateAllDescriptions(): array
    {
        $descriptions = [];
        foreach (array_keys($this->sites) as $index) {
            $descriptions[] = $this->generateDescription($index);
        }
        return $descriptions;
    }

    /**
     * 将站点元信息导出为关联数组，便于JSON序列化或模板使用
     *
     * @return array
     */
    public function export(): array
    {
        $exported = [];
        foreach ($this->sites as $site) {
            $exported[] = [
                'name'        => $site['name'],
                'url'         => $site['url'],
                'description' => $site['description'],
                'keywords'    => $site['keywords'],
                'language'    => $site['language'],
                'author'      => $site['author'],
            ];
        }
        return $exported;
    }
}

// ---------- 使用示例 ----------
$meta = new SiteMeta();

// 添加一个额外的站点
$meta->addSite(
    '爱游戏攻略站',
    '海量游戏攻略、秘籍与技巧分享',
    ['爱游戏', '游戏攻略', '秘籍', '技巧'],
    'https://guides.i-game.example.com'
);

// 生成并输出第一个站点的描述
echo $meta->generateDescription(0) . "\n";

// 生成并输出所有站点的描述
foreach ($meta->generateAllDescriptions() as $desc) {
    echo $desc . "\n";
}

// 导出全部元信息（可用于调试或API返回）
// print_r($meta->export());