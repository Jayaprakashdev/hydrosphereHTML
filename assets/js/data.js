// price list data 
const priceList = [

    /* ================= WATER PURIFIERS ================= */
    { id: 1, category: "Purifier", model: "CHROME CLASSIC +", brand: "CHROME", price: 21000, offer: 18000, color: "WHITE", kit: "6000 LITERS - 3600" },
    { id: 2, category: "Purifier", model: "CHROME UNIQUE", brand: "CHROME", price: 19999, offer: 17000, color: "BC/WC/BB", kit: "6000 LITERS - 3600" },
    { id: 3, category: "Purifier", model: "SS MISTY NORMAL", brand: "MISTY", price: 17990, offer: 15000, color: "SS COLOUR", kit: "6000 LITRES - 3600" },
    { id: 4, category: "Purifier", model: "SS MISTY HOT & NORMAL (WHITE)", brand: "MISTY", price: 26990, offer: 24000, color: "SS WHITE COLOR", kit: "6000 LITRES - 3600" },
    { id: 5, category: "Purifier", model: "SS MISTY HOT & NORMAL (GOLD)", brand: "MISTY", price: 29990, offer: 25000, color: "SS GOLD COLOR", kit: "7000 LITRES - 3600" },
    { id: 6, category: "Purifier", model: "NOBACT UNDERSINK", brand: "MISTY", price: 17900, offer: 15000, color: "WHITE COLOR", kit: "7000 LITRES - 3600" },
    { id: 7, category: "Purifier", model: "SS MISTY HNC", brand: "MISTY", price: 49000, offer: 37000, color: "SS WITE COLOR", kit: "7000 LITERS - 3600" },
    { id: 8, category: "Purifier", model: "WHALE", brand: "MISTY", price: 20000, offer: 17000, color: "WHITE", kit: "12000 LITER - 5500" },
    { id: 9, category: "Purifier", model: "GENPURE", brand: "MISTY", price: 14900, offer: 11900, color: "WHITE/BLACK", kit: "7000 LITERS - 3600" },
    { id: 10, category: "Purifier", model: "PYOORN", brand: "MISTY", price: 19990, offer: 16990, color: "WHITE", kit: "7000 LITERS - 3600" },
    { id: 11, category: "Purifier", model: "OLIVAR", brand: "OLIVAR", price: 14500, offer: 11500, color: "WHITE/BLACK", kit: "7000 LITERS - 3600" },
    { id: 12, category: "Purifier", model: "M PURE", brand: "MISTY", price: 14999, offer: 11999, color: "WHITE/BLUE", kit: "7000 LITERS - 3600" },
    { id: 13, category: "Purifier", model: "SWAN", brand: "MISTY", price: 13900, offer: 10900, color: "WHITE", kit: "7000 LITERS - 3600" },

    /* ================= HYDROSPHERE MODELS ================= */
    { id: 14, category: "Hydrosphere", model: "DOLPIN", price: 11000, offer: 8000, color: "B/M/G/G/W", kit: "7000 LITERS - 3600/2800" },
    { id: 15, category: "Hydrosphere", model: "AQUA 2090", price: 13000, offer: 10000, color: "B/G/A/W/B/G", kit: "7000 LITERS - 3600/2800" },
    { id: 16, category: "Hydrosphere", model: "AQUA GRAND +", price: 13000, offer: 10000, color: "W/B", kit: "7000 LITERS - 3600/2800" },
    { id: 17, category: "Hydrosphere", model: "AQUA LEXZON", price: 12000, offer: 9000, color: "B/G/A/W/B/G", kit: "7000 LITERS - 3600/2800" },
    { id: 18, category: "Hydrosphere", model: "AQUA 9090", price: 14500, offer: 11500, color: "B/W/G", kit: "7000 LITERS - 3600/2800" },
    { id: 19, category: "Hydrosphere", model: "AQUA JADE", price: 13000, offer: 10000, color: "B/G/A", kit: "7000 LITERS - 3600/2800" },
    { id: 20, category: "Hydrosphere", model: "AQUA XL", price: 13000, offer: 10000, color: "B/G/A", kit: "7000 LITERS - 3600/2800" },
    { id: 21, category: "Hydrosphere", model: "PUROSIS", price: 14500, offer: 11500, color: "W/B/G/G/A", kit: "7000 LITERS - 3600/2800" },
    { id: 22, category: "Hydrosphere", model: "URBAN", price: 13500, offer: 10500, color: "W/B/G/G/A", kit: "7000 LITERS - 3600/2800" },
    { id: 23, category: "Hydrosphere", model: "MEGANA", price: 12000, offer: 9000, color: "B/G/A/W/B/G", kit: "7000 LITERS - 3600/2800" },
    { id: 24, category: "Hydrosphere", model: "HI FLOW", price: 12500, offer: 9500, color: "B/G/A/W/B/G", kit: "7000 LITERS - 3600/2800" },
    { id: 25, category: "Hydrosphere", model: "STROM", price: 13000, offer: 10000, color: "B/G/A/W/B/G", kit: "7000 LITERS - 3600/2800" },
    { id: 26, category: "Hydrosphere", model: "VIVA", price: 14500, offer: 11500, color: "B/W/G", kit: "7000 LITERS - 3600/2800" },
    { id: 27, category: "Hydrosphere", model: "LXONE", price: 14500, offer: 11500, color: "B/G/A/W/B/G", kit: "7000 LITERS - 3600/2800" },
    { id: 28, category: "Hydrosphere", model: "NEXON UNDERSINK", price: 16500, offer: 13500, color: "B/W/G", kit: "7000 LITERS - 3600/2800" },
    { id: 29, category: "Hydrosphere", model: "I PEARS", price: 14500, offer: 11500, color: "W/B/G/G/A", kit: "7000 LITERS - 3600/2800" },
    { id: 30, category: "Hydrosphere", model: "ROMA", price: 13000, offer: 11000, color: "W/B/G/G/A", kit: "7000 LITERS - 3600/2800" },
    { id: 31, category: "Hydrosphere", model: "INNOVICA", price: 15500, offer: 12500, color: "B/G/A/W/B/G", kit: "7000 LITERS - 3600/2800" },

    /* ================= JUMBO / SCALE FREE ================= */
    { id: 32, category: "Jumbo", model: "JUMBO 10 INC", price: 6500, offer: 3000, color: "B/W", kit: "3 MONTHS " },
    { id: 33, category: "Jumbo", model: "JUMBO 20 INC VIRGIN (HEAVY)", price: 10500, offer:7000, color: "B/W", kit: "6 MONTHS" },
    { id: 34, category: "Jumbo", model: "JUMBO 20 INC WHITE (HEAVY)", price: 11500, offer:8000, color: "W", kit: "6 MONTHS" },
    { id: 35, category: "Scale Free", model: "TAP SCALE FREE", price: 2000, offer:1500, color: "TRANSPARANT", kit: "6 MONTHS" },
    { id: 36, category: "Scale Free", model: "SHOWER SCALE FREE", price: 3000, offer:2500, color: "SS COVER", kit: "6 MONTHS" },
    { id: 37, category: "Scale Free", model: "WASHING MACHINE SCALE FREE", price: 2499, offer:2200, color: "TRANSPARANT", kit: "6 MONTHS" },
    { id: 38, category: "Scale Free", model: "JUMBO 10 INC SCALE FREE", price: 8500, offer:7000, color: "WHITE", kit: "6 MONTHS" },

    /* ================= SOFTENER ================= */
    { id: 39, category: "Softener", model: "MINI SOFTENER", price: 12975, offer:12975, color: "WHITE", kit: "1 YEAR" },
    { id: 40, category: "Softener", model: "32 LTR VESSEL SOFTENER", price: 39000, offer:32000, color: "BLUE", kit: "1 YEAR" },
    { id: 41, category: "Softener", model: "50 LTR VESSEL SOFTENER (AUTO)", price: 58000, offer:48000, color: "BLUE", kit: "1 YEAR" },
    { id: 42, category: "Softener", model: "75 LTR VESSEL SOFTENER (AUTO)", price: 68000, offer:58000, color: "WHITE", kit: "1 YEAR" },
    { id: 43, category: "Softener", model: "100 LTR VESSEL SOFTENER (AUTO)", price: 85000, offer:82000, color: "WHITE", kit: "2 YEAR" },
    { id: 44, category: "Softener", model: "150 LTR VESSEL SOFTENER (AUTO)", price: 95000, offer:92000, color: "WHITE", kit: "2 YEAR" },

    /* ================= FULL CLOSED ================= */
    { id: 45, category: "Full Closed", model: "10A TABLE TOP – FULL CLOSED", price: 22560, offer:19999, color: "WHITE WITH BLUE", kit: "1 YEAR" },
    { id: 46, category: "Full Closed", model: "BATHROOM SOFTENER – AUTO", price: 35000, offer:29999, color: "ICE WHITE", kit: "1 YEAR" },
    { id: 47, category: "Full Closed", model: "C-20 FULL CLOSED WITH BRINE", price: 45400, offer:39999, color: "ICE WHITE", kit: "1 YEAR" },
    { id: 48, category: "Full Closed", model: "C-45AD FULL CLOSED WITH BRINE", price: 71100, offer:49999, color: "ICE WHITE", kit: "1 YEAR" },

    /* ================= ACCESSORIES ================= */
    { id: 49, category: "Accessory", model: "MOTOR", price: 2599, offer:2599, color: "WHITE", kit: "2 YEAR" },
    { id: 50, category: "Accessory", model: "ADAPTOR", price: 850, offer:850, color: "BLACK", kit: "1 YEAR" },
    { id: 51, category: "Accessory", model: "SV", price: 700, offer:700, color: "BLUE", kit: "1 YEAR" },
    { id: 52, category: "Accessory", model: "FILTER KIT", price: 3699, offer:3699, color: "BLUE/WHITE", kit: "1 YEAR" },
    { id: 53, category: "Accessory", model: "FLOAT (KENT)", price: 350, offer:350, color: "WHITE", kit: "1 YEAR" },
    { id: 54, category: "Accessory", model: "TUBE (MISTY)", price: 35, offer:35, color: "WHITE", kit: "1 YEAR" },
    { id: 55, category: "Accessory", model: "PER BLOW HEAVY", price: 750, offer:750, color: "WHITE", kit: "1 YEAR" },
    { id: 56, category: "Accessory", model: "PER BLOW TRANSPARENT HEAVY", price: 475, offer:475, color: "BLUE", kit: "1 YEAR" },
    { id: 57, category: "Accessory", model: "CANDLE (AQUA GUARD)", price: 450, offer:450, color: "BLUE/WHITE", kit: "1 YEAR" },
    { id: 58, category: "Accessory", model: "AQUA GUARD BLOW", price: 1200, offer:1200, color: "WHITE/BLACK", kit: "1 YEAR" }

];