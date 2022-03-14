//
//  EpgModel.swift
//  Streamly
//
//  Created by Rohit Singh on 6/23/20.
//  Copyright © 2020 Rohit Singh. All rights reserved.
//

import Foundation
struct EPGResponse : Codable {

    let data : ChannelPagingData?
    let date:String?
    let success:Bool?
    let message: String?
    let responseCode: Int?

    enum CodingKeys: String, CodingKey {

        case data = "data"
        case date = "date"
        case success = "success"
        case responseCode = "response_code"
        case message = "message"
    }

    init(from decoder: Decoder) throws {
        let values = try decoder.container(keyedBy: CodingKeys.self)
        if let getData = try values.decodeIfPresent(ChannelPagingData.self, forKey: .data) {
            data = getData
        } else {
            data = nil
        }
//        data = try values.decodeIfPresent([ChannelDetails].self, forKey: .data)
        responseCode = try values.decodeIfPresent(Int.self, forKey: .responseCode)
        message = try values.decodeIfPresent(String.self, forKey: .message)
        date = try values.decodeIfPresent(String.self, forKey: .date)
        success = try values.decodeIfPresent(Bool.self, forKey: .success)

    }
}

struct ChannelPagingData: Codable {
    let currentPage: Int?
    let data:  [ChannelDetails]?
    let firstPageUrl: String?
    let from: Int?
    let lastPage: Int?
    let lastPageUrl: String?
    let nextPageUrl: String?
    let path: String?
    let perPage: Int?
//    let prevPageUrl: String?
    let to: Int?
    let total: Int?
    
    enum CodingKeys: String, CodingKey {
        case currentPage = "current_page"
        case data = "data"
        case firstPageUrl = "first_page_url"
        case from = "from"
        case lastPage = "last_page"
        case lastPageUrl = "last_page_url"
        case nextPageUrl = "next_page_url"
        case path = "path"
        case perPage = "per_page"
//        case prevPageUrl = "prev_page_url"
        case to = "to"
        case total = "total"
    }
}
struct ChannelDetails : Codable {

    var id:Int?
    var StationName :String?
    var url :String?
    var logo :String?
    var CallSign:String?
    var programs :[ProgramArray]?

    init(id: Int, stationName: String, url: String, logo: String, callSign: String, programs: [ProgramArray]) {
        self.id = id
        self.StationName = stationName
        self.url = url
        self.logo = logo
        self.CallSign = callSign
        self.programs = programs
    }
    enum CodingKeys: String, CodingKey {

        case StationName = "StationName"
        case url = "url"
        case logo = "logo"
        case programs = "programs"
        case CallSign = "callSign"
        case id = "id"


    }

    init(from decoder: Decoder) throws {
        let values = try decoder.container(keyedBy: CodingKeys.self)
        StationName = try values.decodeIfPresent(String.self, forKey: .StationName)
        CallSign = try values.decodeIfPresent(String.self, forKey: .CallSign)
        id = try values.decodeIfPresent(Int.self, forKey: .id)
        url = try values.decodeIfPresent(String.self, forKey: .url)
        logo = try values.decodeIfPresent(String.self, forKey: .logo)
        programs = try values.decodeIfPresent([ProgramArray].self, forKey: .programs)

    }


}

struct ProgramArray : Codable {

    let end : TimeInterval?
    let start : TimeInterval?
    let title:String?
    let description:String?
    let starttz: TimeInterval?
    let endtz: TimeInterval?
    let TimeZoneName: String?

    var startDate: Date {
        return Date(timeIntervalSince1970: TimeInterval(self.start ?? 0))
     }
    
    var endDate: Date {
        return Date(timeIntervalSince1970: TimeInterval(self.end ?? 0))
     }
    
    var duration: TimeInterval {
        let duration = self.endDate.addingTimeInterval(-(self.start ?? 0)).timeIntervalSince1970
       return duration
     }


    enum CodingKeys: String, CodingKey {

        case TimeZoneName
        case endtz
        case starttz
        case end = "end"
        case start = "start"
        case title = "title"
        case description = "description"
    }

    init(from decoder: Decoder) throws {
        let values = try decoder.container(keyedBy: CodingKeys.self)
        end = try values.decodeIfPresent(TimeInterval.self, forKey: .end)
        start = try values.decodeIfPresent(TimeInterval.self, forKey: .start)
        description = try values.decodeIfPresent(String.self, forKey: .description)
        title = try values.decodeIfPresent(String.self, forKey: .title)
        starttz = try values.decodeIfPresent(TimeInterval.self, forKey: .starttz)
        endtz = try values.decodeIfPresent(TimeInterval.self, forKey: .endtz)
        TimeZoneName = try values.decodeIfPresent(String.self, forKey: .TimeZoneName)
    }
    
    init(startTZ: TimeInterval, endTZ: TimeInterval, start: TimeInterval, end: TimeInterval, title: String, timezoneName: String, description: String) {
        self.starttz = startTZ
        self.endtz = endTZ
        self.start = start
        self.end = end
        self.title = title
        self.description = description
        self.TimeZoneName = timezoneName
        
    }

}
