-- 新增标签表 
CREATE TABLE "main"."lm_tag" (
  "id" INTEGER PRIMARY KEY AUTOINCREMENT,
  "name" TEXT(128) DEFAULT "",
  "pass" TEXT(128) DEFAULT "",
  "add_time" integer(10) DEFAULT 0,
  "up_time" integer(10) DEFAULT 0,
  "expire" integer(10) DEFAULT 0,
  "views" integer DEFAULT 0,
  "mark" TEXT(13),
  "extra" TEXT(512) DEFAULT "",
  CONSTRAINT "name" UNIQUE ("name"),
  CONSTRAINT "mark" UNIQUE ("mark")
);

-- 增加链接标签id
ALTER TABLE on_links ADD tagid INTEGER DEFAULT 0;