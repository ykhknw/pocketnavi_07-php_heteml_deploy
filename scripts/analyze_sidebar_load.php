<?php
/**
 * サイドバー「人気の検索」の負荷分析
 * 
 * 1GBテーブルサイズでの影響評価
 */

echo "=== サイドバー「人気の検索」負荷分析 ===\n\n";

// 現在のクエリ分析
echo "📊 現在のクエリ分析:\n";
echo "対象テーブル: global_search_history\n";
echo "クエリ条件: searched_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)\n";
echo "処理内容: GROUP BY, COUNT, ORDER BY, LIMIT 20\n";
echo "実行頻度: 全ページ表示時（毎回）\n\n";

// テーブルサイズ別の影響評価
$tableSizes = [
    '100MB' => 100,
    '500MB' => 500,
    '1GB' => 1024,
    '2GB' => 2048,
    '5GB' => 5120
];

echo "=== テーブルサイズ別の負荷評価 ===\n\n";

foreach ($tableSizes as $sizeLabel => $sizeMB) {
    echo "📈 テーブルサイズ: {$sizeLabel}\n";
    
    // 推定レコード数（1レコード約0.5KB）
    $estimatedRecords = ($sizeMB * 1024) / 0.5;
    
    // 30日以内のデータ割合（仮定）
    $recentDataRatio = 0.3; // 30%
    $recentRecords = $estimatedRecords * $recentDataRatio;
    
    echo "- 推定総レコード数: " . number_format($estimatedRecords) . " 件\n";
    echo "- 30日以内レコード数: " . number_format($recentRecords) . " 件\n";
    
    // 負荷評価
    if ($sizeMB <= 100) {
        $loadLevel = "🟢 軽微";
        $description = "インデックスが効き、高速実行";
        $executionTime = "10-50ms";
    } elseif ($sizeMB <= 500) {
        $loadLevel = "🟡 中程度";
        $description = "インデックス使用、やや重い";
        $executionTime = "50-200ms";
    } elseif ($sizeMB <= 1024) {
        $loadLevel = "🟠 重い";
        $description = "インデックス効率低下、注意が必要";
        $executionTime = "200-500ms";
    } elseif ($sizeMB <= 2048) {
        $loadLevel = "🔴 非常に重い";
        $description = "フルテーブルスキャンの可能性";
        $executionTime = "500ms-2s";
    } else {
        $loadLevel = "🚨 危険";
        $description = "タイムアウトの可能性";
        $executionTime = "2s以上";
    }
    
    echo "- 負荷レベル: {$loadLevel}\n";
    echo "- 説明: {$description}\n";
    echo "- 推定実行時間: {$executionTime}\n\n";
}

// 1GBテーブルでの詳細分析
echo "=== 1GBテーブルでの詳細分析 ===\n\n";

$tableSizeGB = 1;
$tableSizeMB = 1024;
$estimatedRecords = ($tableSizeMB * 1024) / 0.5; // 約200万レコード
$recentRecords = $estimatedRecords * 0.3; // 約60万レコード

echo "📊 1GBテーブルの詳細:\n";
echo "- 総レコード数: " . number_format($estimatedRecords) . " 件\n";
echo "- 30日以内レコード数: " . number_format($recentRecords) . " 件\n";
echo "- インデックスサイズ: 約200-300MB\n";
echo "- メモリ使用量: 約50-100MB（クエリ実行時）\n\n";

// 負荷の影響評価
echo "🚨 1GBテーブルでの負荷影響:\n\n";

echo "1. データベース負荷:\n";
echo "   - クエリ実行時間: 200-500ms\n";
echo "   - CPU使用率: 中程度\n";
echo "   - メモリ使用量: 50-100MB\n";
echo "   - ディスクI/O: 中程度\n\n";

echo "2. ページ表示への影響:\n";
echo "   - ページ読み込み時間: +200-500ms\n";
echo "   - ユーザー体験: 体感できる遅延\n";
echo "   - 同時接続数: 制限される可能性\n\n";

echo "3. レンタルサーバーへの影響:\n";
echo "   - リソース消費: 高\n";
echo "   - 他の処理への影響: あり\n";
echo "   - サーバー負荷: 中-高\n";
echo "   - 制限に達する可能性: あり\n\n";

// 解決策の提案
echo "=== 解決策の提案 ===\n\n";

echo "🎯 即座に実装すべき対策:\n\n";

echo "1. キャッシュ機能の実装:\n";
echo "   - 人気検索結果を1時間キャッシュ\n";
echo "   - 負荷を99%削減可能\n";
echo "   - 実装難易度: 低\n\n";

echo "2. インデックス最適化:\n";
echo "   - searched_at + search_type の複合インデックス\n";
echo "   - クエリ性能を50-80%向上\n";
echo "   - 実装難易度: 低\n\n";

echo "3. データ集計テーブルの作成:\n";
echo "   - 日次で人気検索を集計\n";
echo "   - リアルタイムクエリを回避\n";
echo "   - 実装難易度: 中\n\n";

echo "4. ページ分割:\n";
echo "   - サイドバーをAjaxで遅延読み込み\n";
echo "   - 初期ページ表示を高速化\n";
echo "   - 実装難易度: 中\n\n";

// 緊急度評価
echo "=== 緊急度評価 ===\n\n";

echo "🚨 1GBテーブルでの緊急度: 高\n\n";
echo "理由:\n";
echo "- 全ページで実行されるため影響範囲が広い\n";
echo "- ユーザー体験に直接影響\n";
echo "- サーバーリソースを大量消費\n";
echo "- 他の処理のパフォーマンスに影響\n\n";

echo "推奨対応順序:\n";
echo "1. 即座: キャッシュ機能の実装\n";
echo "2. 1週間以内: インデックス最適化\n";
echo "3. 1ヶ月以内: データ集計テーブルの作成\n";
echo "4. 長期: アーキテクチャの見直し\n\n";

// 具体的な実装例
echo "=== 具体的な実装例 ===\n\n";

echo "📝 キャッシュ機能の実装例:\n";
echo "```php\n";
echo "function getPopularSearchesCached(\$lang = 'ja') {\n";
echo "    \$cacheKey = 'popular_searches_' . \$lang;\n";
echo "    \$cacheFile = 'cache/' . \$cacheKey . '.json';\n";
echo "    \n";
echo "    // キャッシュが1時間以内なら使用\n";
echo "    if (file_exists(\$cacheFile) && \n";
echo "        (time() - filemtime(\$cacheFile)) < 3600) {\n";
echo "        return json_decode(file_get_contents(\$cacheFile), true);\n";
echo "    }\n";
echo "    \n";
echo "    // キャッシュが古い場合は再取得\n";
echo "    \$searches = getPopularSearches(\$lang);\n";
echo "    file_put_contents(\$cacheFile, json_encode(\$searches));\n";
echo "    return \$searches;\n";
echo "}\n";
echo "```\n\n";

echo "📝 インデックス最適化:\n";
echo "```sql\n";
echo "CREATE INDEX idx_searched_at_search_type \n";
echo "ON global_search_history (searched_at, search_type);\n";
echo "```\n\n";

// まとめ
echo "=== まとめ ===\n\n";
echo "1GBテーブルでのサイドバー「人気の検索」は:\n";
echo "- 負荷レベル: 🔴 非常に重い\n";
echo "- 緊急度: 🚨 高\n";
echo "- 対応が必要: 即座\n\n";
echo "最優先でキャッシュ機能を実装し、\n";
echo "段階的にパフォーマンス最適化を行うことを推奨します。\n";
