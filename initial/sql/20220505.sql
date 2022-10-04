-- 新增申请收录表 
CREATE TABLE "main"."lm_apply" (
  "id" INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
  "iconurl" TEXT(512) DEFAULT "",
  "title" TEXT(512) DEFAULT "",
  "url" TEXT(512) DEFAULT "",
  "email" TEXT(128) DEFAULT "",
  "ip" TEXT(16) DEFAULT "",
  "ua" TEXT(512) DEFAULT "",
  "time" integer DEFAULT 0,
  "state" integer DEFAULT 0,
  "extend" TEXT DEFAULT "",
  "category_id" INTEGER DEFAULT 0,
  "category_name" TEXT(512) DEFAULT "",
  "description" TEXT(512),
  CONSTRAINT "url" UNIQUE ("url" ASC)
);