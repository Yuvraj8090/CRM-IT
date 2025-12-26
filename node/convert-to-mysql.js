import fs from 'fs';
import path from 'path';
import { fileURLToPath } from 'url';

// Get __dirname in ES module
const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

const inputFile = path.join(__dirname, 'all_incident_till_2025.sql');
const outputFile = path.join(__dirname, 'mysql.sql');

// ✅ Read file properly as UTF-16LE (common for SQL Server .sql exports)
let sql = fs.readFileSync(inputFile, 'utf16le'); // use utf16le instead of utf-8

// 1️⃣ Remove SQL Server-specific lines
sql = sql.replace(/SET ANSI_NULLS ON/gi, '');
sql = sql.replace(/SET QUOTED_IDENTIFIER ON/gi, '');
sql = sql.replace(/\bGO\b/gi, '');

// 2️⃣ Remove [dbo]. prefix
sql = sql.replace(/\[dbo\]\./gi, '');

// 3️⃣ Convert data types
sql = sql.replace(/\[nvarchar\]\(\d+\)/gi, 'VARCHAR(255)');
sql = sql.replace(/\[int\]/gi, 'INT');
sql = sql.replace(/\[tinyint\]/gi, 'TINYINT');
sql = sql.replace(/\[bit\]/gi, 'TINYINT(1)');
sql = sql.replace(/\[decimal\]\((\d+),\s*(\d+)\)/gi, 'DECIMAL($1,$2)');
sql = sql.replace(/\[bigint\]/gi, 'BIGINT');
sql = sql.replace(/\[date\]/gi, 'DATE');

// 4️⃣ Handle primary keys
sql = sql.replace(/CONSTRAINT \[PK_[^\]]+\] PRIMARY KEY \(\[([^\]]+)\]\)/gi, 'PRIMARY KEY ($1)');

// 5️⃣ Remove CLUSTERED and ON [PRIMARY]
sql = sql.replace(/CLUSTERED/gi, '');
sql = sql.replace(/WITH \(.+?\)/gi, '');
sql = sql.replace(/ON \[PRIMARY\]/gi, '');

// 6️⃣ Handle IDENTITY → AUTO_INCREMENT
sql = sql.replace(/\[id\] INT NOT NULL IDENTITY\(1,1\)/gi, '`id` INT NOT NULL AUTO_INCREMENT');

// 7️⃣ Fix INSERT statements
sql = sql.replace(/N'/g, "'");
sql = sql.replace(/CAST\('([^']+)'\s+AS\s+Date\)/gi, "'$1'");

// 8️⃣ Replace brackets [] in column names with backticks ``
sql = sql.replace(/\[([^\]]+)\]/g, '`$1`');

// 9️⃣ Save output as UTF-8
fs.writeFileSync(outputFile, sql, 'utf8');

console.log('✅ Converted MySQL file saved correctly at:', outputFile);
