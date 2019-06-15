<?php
include_once __DIR__ . '/../../templates/nav/nav.html.php';
include_once __DIR__ . '/../../templates/nav/sec-nav.html.php';
include_once __DIR__ . '/../models/doctor.php';
require_once __DIR__ . '/../../twig.php';

echo $twig->render('doctors/list.twig', [
                    'doctors' => $doctors_list]);
echo $twig->render('doctors/add.twig', [
					'titles' => $titles,
					'branches' => $branches,
					'experiences' => $experiences]);

echo '        </div>
    </div>

<script src="/fhcc/js/styles.js"></script>
</body>

</html>';