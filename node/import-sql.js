const mysql = require("mysql2/promise");

console.log("RUNNING NEW CLEAN SCRIPT ✅");

// DB
const db = mysql.createPool({
  host: "127.0.0.1",
  user: "root",
  password: "",
  database: "joomla_db",
});

// ---------- HELPERS ----------
const cleanLatLng = v => {
  if (v === null || v === undefined) return null;
  const s = String(v).trim();
  if (s === "" || s === "0") return null;
  const n = parseFloat(s);
  return Number.isFinite(n) ? n : null;
};

const cleanDate = v => {
  if (v === null || v === undefined) return null;
  const s = String(v).trim();
  if (s === "" || s === "0") return null;
  const d = new Date(s);
  return isNaN(d) ? null : d.toISOString().slice(0, 10);
};

const cleanTime = v => {
  if (!v) return "00:00:00";
  let s = String(v).replace(/[`[\]]/g, "").trim();
  if (!s || s === "0" || s === "0:00") return "00:00:00";

  const m = s.match(/^(\d{1,2}):(\d{1,2})(?:\s*(AM|PM))?$/i);
  if (!m) return "00:00:00";

  let h = +m[1];
  let min = +m[2];
  const ap = m[3]?.toUpperCase();

  if (ap === "PM" && h < 12) h += 12;
  if (ap === "AM" && h === 12) h = 0;
  if (h > 23 || min > 59) return "00:00:00";

  return `${String(h).padStart(2,"0")}:${String(min).padStart(2,"0")}:00`;
};

// ---------- MIGRATION ----------
(async () => {
  const [rows] = await db.query("SELECT * FROM incidentlog");

  let ok = 0, fail = 0;

  const sql = `
    INSERT INTO incidents (
      incident_uid, incident_name, incident_type_id, steps, incident_through,
      state, district, tehsil_id, village,
      latitude, longitude, incident_date, incident_time,
      big_animals_died, small_animals_died,
      partially_house, severely_house, fully_house, cowshed_house,
      file_path, agriculture_land_loss_hectare,
      created_at, updated_at,
      helicopter_sorties, electricity_line_damage, water_pipeline_damage,
      hut_count, hen_count,
      dead_human_count, injured_human_count, missing_human_count,
      other_animal_count, road_damage, punha_sthapanna_road
    )
    VALUES (
      ?, ?, ?, ?, NULL,
      ?, ?, ?, ?,
      ?, ?, ?, ?,
      ?, ?,
      ?, ?, ?, ?,
      NULL, ?,
      NOW(), FROM_UNIXTIME(?),
      ?, ?, ?,
      0, 0,
      ?, ?, ?,
      0, ?, ?
    )
  `;

  for (const il of rows) {
    try {
      await db.execute(sql, [
        il.id,
        il.inclocation,
        il.inctype || null,
        il.incstatus || null,

        il.inclocation,
        il.incdid || null,
        il.inctid || null,
        il.inclocation,

        cleanLatLng(il.inclat),
        cleanLatLng(il.inclon),
        cleanDate(il.incdate),
        cleanTime(il.inctime),

        il.incbcat || 0,
        il.incscat || 0,
        il.inchousep || 0,
        il.incxhoused || 0,
        il.inchoused || 0,
        il.inccowshed || 0,

        il.incagri || 0,
        il.lutime || 0,

        il.hshortiess || 0,
        il.incpowerd || 0,
        il.incwaterd || 0,

        il.incdhuman || 0,
        il.incihuman || 0,
        il.incmhuman || 0,

        il.incroadd || 0,
        il.incroadr || 0
      ]);

      ok++;
    } catch (e) {
      fail++;
      console.error("❌ Failed:", il.id, e.message);
    }
  }

  console.log("✅ DONE");
  console.log("Inserted:", ok);
  console.log("Failed:", fail);
  process.exit();
})();
