//
//  SearchResponse.swift
//  Streamly
//
//  Created by Rohit Singh on 6/15/20.
//  Copyright © 2020 Rohit Singh. All rights reserved.
//

import Foundation

struct ChannelResponse : Codable {
    
    let message : ChannelData?
    
    enum CodingKeys: String, CodingKey {
        
        case message = "message"
        
    }
    
    init(from decoder: Decoder) throws {
        let values = try decoder.container(keyedBy: CodingKeys.self)
        message = try values.decodeIfPresent(ChannelData.self, forKey: .message)
        
    }
}

struct ChannelData : Codable {
    
    let current_page : Int?
    let data :    [ChannelArray]?
    
    enum CodingKeys: String, CodingKey {
        
        case current_page = "current_page"
        case data = "data"
        
    }
    
    init(from decoder: Decoder) throws {
        let values = try decoder.container(keyedBy: CodingKeys.self)
        current_page = try values.decodeIfPresent(Int.self, forKey: .current_page)
        data = try values.decodeIfPresent([ChannelArray].self, forKey: .data)
        
    }
    
    
}

struct ChannelArray : Codable {
    
    //  let connected_devices : Int?
    let name : String?
    let url : String?
    let logo:String?
    
    enum CodingKeys: String, CodingKey {
        
        //   case connected_devices = "connected_devices"
        case name = "name"
        case url = "url"
        case logo = "logo"
    }
    
    init(from decoder: Decoder) throws {
        let values = try decoder.container(keyedBy: CodingKeys.self)
        //  connected_devices = try values.decodeIfPresent(Int.self, forKey: .connected_devices)
        name = try values.decodeIfPresent(String.self, forKey: .name)
        url = try values.decodeIfPresent(String.self, forKey: .url)
        logo = try values.decodeIfPresent(String.self, forKey: .logo)
        
    }
    
}
