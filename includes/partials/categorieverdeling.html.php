<script type="text/javascript">
    $(function () {
        var data = [
<?php
$categoriedata = checkCategorie();
$gegevens = getAllExamquestionCategories();
$x = count($gegevens);
$e = 1;
foreach ($gegevens as $gegeven) {
    ?>
            { label: "<?php
    foreach ($categoriedata as $t) {
        $q = $t['categorieomschrijving'];
        if ($gegeven['categorie_id'] == $t['categorie_id']) {
            echo $q;
        }
    }
    ?>", data:"<?php echo $gegeven['count(categorie_id)']; ?>"}
    <?php
    $e++;
    if ($x >= $e) {
        echo",";
    }
    ?>
    <?php
}
?>
        ];
                var placeholder = $("#placeholder");
        placeholder.unbind();
        $("#title").text("Resultaten");
        $("#description").text("The pie can be tilted at an angle.");
        $.plot(placeholder, data, {
            series: {
                pie: {
                    show: true
                }
            },
            legend: {
                show: false
            }
        });
        setCode([
            "$.plot('#placeholder', data, {",
            "    series: {",
            "        pie: {",
            "            show: true,",
            "            radius: 1,",
            "            tilt: 0.5,",
            "            label: {",
            "                show: true,",
            "                radius: 1,",
            "                formatter: labelFormatter,",
            "                background: {",
            "                    opacity: 0.8",
            "                }",
            "            },",
            "            combine: {",
            "                color: '#999',",
            "                threshold: 0.1",
            "            }",
            "        }",
            "    },",
            "    legend: {",
            "        show: false",
            "    }",
            "});",
        ]);
    });
// A custom label formatter used by several of the plots
    function labelFormatter(label, series) {
        return "<div style='font-size:8pt; text-align:center; padding:2px; color:white;'>" + label + "<br/>" + Math.round(series.percent) + "%</div>";
    }
    function setCode(lines) {
        $("#code").text(lines.join("\n"));
    }
</script>
<style>
    .categorieverdeling-container {
        width: 100%;
        height: 400px;
    }
    .categorieverdeling-placeholder {
        width: 100%;
        height: 90%;
        font-size: 10px;
        line-height: 1.0em;
    }
</style>
<div class="categorieverdeling-container">
    <div id="placeholder" class="categorieverdeling-placeholder"></div>
    <div id="menu">
        <button style="display: none;" id="example-1"></button><!--deze regel niet weghalen-->

    </div>
</div>
