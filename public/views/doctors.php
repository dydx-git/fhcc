<?php
include_once __DIR__ . '/../../templates/nav/nav.html.php';
include_once __DIR__ . '/../../templates/nav/sec-nav.html.php';
include_once __DIR__ . '/../models/doctor.php';
require_once __DIR__ . '/../../twig.php';

echo $twig->render('doctors/list.twig', [
                    'doctors' => $doctors]);
echo $twig->render('doctors/add.twig');

echo '        </div>
    </div>

<script src="/fhcc/node_modules/bulma-slider/dist/js/bulma-slider.min.js"></script>
<script src="/fhcc/js/styles.js"></script>
</body>

</html>';