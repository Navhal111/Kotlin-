package pistalix.gujaratibalgeetvideo.gujaratirhymes

import android.content.Context
import android.content.SharedPreferences
import android.preference.PreferenceManager
import com.readystatesoftware.sqliteasset.SQLiteAssetHelper
import java.util.ArrayList

class DatabaseHelper(context: Context) : SQLiteAssetHelper(context, DB_Name, null, 1) {

    internal var shared: SharedPreferences

    init {
        this.shared = PreferenceManager.getDefaultSharedPreferences(context)
    }

    fun getlist(): List<String> {
        val namelist = ArrayList<String>()
        val cursor = writableDatabase.rawQuery("SELECT bgeet_title FROM bgeet", null)
        if (cursor.moveToFirst()) {
            do {
                namelist.add(cursor.getString(0))
            } while (cursor.moveToNext())
        }
        return namelist
    }

    fun getnum(): List<String> {
        val namelist = ArrayList<String>()
        val cursor = writableDatabase.rawQuery("SELECT bgeet_id FROM bgeet", null)
        if (cursor.moveToFirst()) {
            do {
                namelist.add(cursor.getString(0))
            } while (cursor.moveToNext())
        }
        return namelist
    }

    fun getdescptn(): List<String> {
        val namelist = ArrayList<String>()
        val cursor = writableDatabase.rawQuery("SELECT bgeet_desc FROM bgeet", null)
        if (cursor.moveToFirst()) {
            do {
                namelist.add(cursor.getString(0))
            } while (cursor.moveToNext())
        }
        return namelist
    }

    companion object {
        private val DB_Name = "BalGeet.sqlite"
        private val New_DB_Version = 1
    }
}