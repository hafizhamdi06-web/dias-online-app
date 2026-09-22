-- ============================================================
-- Fix: HPSUSUMBER varchar(10) terlalu sempit utk label fallback
-- 'IHARGABELI2' (11 karakter) -- kepotong diam2 jadi 'IHARGABELI'
-- (menabrak nama SUSUMBER asli lain). Lebarkan kolomnya.
-- ============================================================

ALTER TABLE fhpprii MODIFY HPSUSUMBER VARCHAR(20) NOT NULL;
