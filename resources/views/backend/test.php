SELECT
  joborder.*,
  konfirmasi_joborder.id AS konfirmasi_id,
  konfirmasi_joborder.tgl_muat,
  konfirmasi_joborder.status AS status_konfirmasi,
  konfirmasi_joborder.tgl_bongkar,
  konfirmasi_joborder.berat_muatan,
  konfirmasi_joborder.total_harga
FROM
  `joborder`
  INNER JOIN `konfirmasi_joborder`
    ON `konfirmasi_joborder`.`joborder_id` = `joborder`.`id`
WHERE `konfirmasi_joborder`.`status` = '0'
  OR `konfirmasi_joborder`.`kode_joborder` IN ('230320009,230408001')
  AND `konfirmasi_joborder`.`customer_id` = '1'
  AND (
    LOWER(
      `konfirmasi_joborder`.`kode_joborder`
    ) LIKE '230505031'
    OR LOWER(
      `konfirmasi_joborder`.`tgl_muat`
    ) LIKE '230505031'
    OR LOWER(
      `konfirmasi_joborder`.`tgl_bongkar`
    ) LIKE '230505031'
    OR EXISTS
    (SELECT
      *
    FROM
      `mobil`
    WHERE `joborder`.`mobil_id` = `mobil`.`id`
      AND LOWER(`mobil`.`nomor_plat`) LIKE '230505031')
    OR EXISTS
    (SELECT
      *
    FROM
      `muatan`
    WHERE `joborder`.`muatan_id` = `muatan`.`id`
      AND LOWER(`muatan`.`name`) LIKE '230505031')
    OR EXISTS
    (SELECT
      *
    FROM
      `alamatrute`
    WHERE `joborder`.`first_rute_id` = `alamatrute`.`id`
      AND LOWER(`alamatrute`.`name`) LIKE '230505031')
    OR EXISTS
    (SELECT
      *
    FROM
      `alamatrute`
    WHERE `joborder`.`last_rute_id` = `alamatrute`.`id`
      AND LOWER(`alamatrute`.`name`) LIKE '230505031')
    OR LOWER(
      `konfirmasi_joborder`.`berat_muatan`
    ) LIKE '230505031'
    OR EXISTS
    (SELECT
      *
    FROM
      `rute`
    WHERE `joborder`.`rute_id` = `rute`.`id`
      AND LOWER(`rute`.`harga`) LIKE '230505031')
    OR LOWER(
      `konfirmasi_joborder`.`total_harga`
    ) LIKE '230505031'
    OR EXISTS
    (SELECT
      *
    FROM
      `rute`
    WHERE `joborder`.`rute_id` = `rute`.`id`
      AND LOWER(`rute`.`ritase_tonase`) LIKE '230505031')
  )
ORDER BY `konfirmasi_joborder`.`kode_joborder` DESC
LIMIT 50 OFFSET 0
