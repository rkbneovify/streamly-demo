//
//  EpgModel.swift
//  Streamly
//
//  Created by Rohit Singh on 6/23/20.
//  Copyright © 2020 Rohit Singh. All rights reserved.
//

import Foundation
struct EPGResponse : Codable {

    let data : [ChannelDetails]?
    var date:String?
    var success:Bool?

    enum CodingKeys: String, CodingKey {

        case data = "data"
        case date = "date"
        case success = "success"

    }

    init(from decoder: Decoder) throws {
        let values = try decoder.container(keyedBy: CodingKeys.self)
        data = try values.decodeIfPresent([ChannelDetails].self, forKey: .data)
        date = try values.decodeIfPresent(String.self, forKey: .date)
        success = try values.decodeIfPresent(Bool.self, forKey: .success)

    }
}

struct ChannelDetails : Codable {


    var StationName :String?
    var url :String?
    var logo :String?
    let programs :[ProgramArray]?

    enum CodingKeys: String, CodingKey {

        case StationName = "StationName"
        case url = "url"
        case logo = "logo"
        case programs = "programs"


    }

    init(from decoder: Decoder) throws {
        let values = try decoder.container(keyedBy: CodingKeys.self)
        StationName = try values.decodeIfPresent(String.self, forKey: .StationName)
        url = try values.decodeIfPresent(String.self, forKey: .url)
        logo = try values.decodeIfPresent(String.self, forKey: .logo)
        programs = try values.decodeIfPresent([ProgramArray].self, forKey: .programs)

    }


}

struct ProgramArray : Codable {

    let end : Date?
    let start : Date?
    let title:String?

    enum CodingKeys: String, CodingKey {


        case end = "end"
        case start = "start"
        case title = "title"
    }

    init(from decoder: Decoder) throws {
        let values = try decoder.container(keyedBy: CodingKeys.self)
        end = try values.decodeIfPresent(Date.self, forKey: .end)
        start = try values.decodeIfPresent(Date.self, forKey: .start)
        title = try values.decodeIfPresent(String.self, forKey: .title)

    }

}
