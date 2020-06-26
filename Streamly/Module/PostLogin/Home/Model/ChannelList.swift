//
//  ChannelList.swift
//  Streamly
//
//  Created by Rohit Singh on 6/25/20.
//  Copyright © 2020 Rohit Singh. All rights reserved.
//

import Foundation

struct ChannelListResponse : Codable {

    let data : [ChannelList]?
    let success:Bool?

    enum CodingKeys: String, CodingKey {

        case data = "data"
        case success = "success"

    }

    init(from decoder: Decoder) throws {
        let values = try decoder.container(keyedBy: CodingKeys.self)
        data = try values.decodeIfPresent([ChannelList].self, forKey: .data)
        success = try values.decodeIfPresent(Bool.self, forKey: .success)

    }
}

struct ChannelList : Codable {

    let genrename : [String]?
    var callSign:String?


    enum CodingKeys: String, CodingKey {


        case callSign = "callSign"
        case genrename = "genrename"

    }

    init(from decoder: Decoder) throws {
        let values = try decoder.container(keyedBy: CodingKeys.self)
        genrename = try values.decodeIfPresent([String].self, forKey: .genrename)
        callSign = try values.decodeIfPresent(String.self, forKey: .callSign)


    }
}



